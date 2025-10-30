<?php

declare(strict_types=1);

/*
 * Copyright (C) 2025 Mazarini <mazarini@pm.me>.
 * This file is part of mazarini/symfonai.
 *
 * mazarini/symfonai is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * mazarini/symfonai is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with mazarini/symfonai. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Mazarini\SymfonAI\Service;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiClient
{
    public const GEMINI_API_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $geminiApiKey,
    ) {
    }

    public function generateContent(string $prompt, string $systemPrompt = ''): array
    {
        $payload      = [];
        $systemPrompt = mb_trim($systemPrompt);

        if ($systemPrompt !== '') {
            $payload['system_instruction'] = [
                'parts' => [
                    ['text' => $systemPrompt],
                ],
            ];
        }

        $payload['contents'] = [
            [
                'parts' => [
                    ['text' => mb_trim($prompt)],
                ],
            ],
        ];

        try {
            $response = $this->httpClient->request('POST', self::GEMINI_API_ENDPOINT, [
                'query' => ['key' => $this->geminiApiKey],
                'json'  => $payload,
            ]);

            $data = $response->toArray();

            $answer = 'Error: Received an empty or invalid response from the API.';
            if (!empty($data['candidates'][0]['content']['parts'][0]['text'])) {
                $answer = $data['candidates'][0]['content']['parts'][0]['text'];
            }

            return [
                'answer'   => $answer,
                'request'  => $payload,
                'response' => $data,
            ];
        } catch (\Throwable $e) {
            $errorResponse   = ['message' => $e->getMessage()];
            $responseContent = 'N/A';
            if ($e instanceof ClientException) {
                try {
                    $errorResponse   = $e->getResponse()->toArray(false);
                    $responseContent = json_encode($errorResponse, \JSON_PRETTY_PRINT);
                } catch (\Throwable $jsonE) {
                    $responseContent = $e->getResponse()->getContent(false);
                }
            }

            $answer = 'Error: ' . ($errorResponse['error']['message'] ?? $e->getMessage());

            return [
                'answer'   => $answer,
                'request'  => $payload,
                'response' => ['error' => $errorResponse, 'raw_response' => $responseContent, 'exception' => $e->getMessage()],
            ];
        }
    }
}
