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

use Mazarini\SymfonAI\Request\AiRequestInterface;
use Mazarini\SymfonAI\Request\GeminiRequest;
use Mazarini\SymfonAI\Response\AiResponseInterface;
use Mazarini\SymfonAI\Response\GeminiResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiClient extends AbstractAiClient
{
    public const GEMINI_API_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct(
        HttpClientInterface $httpClient,
        private readonly string $geminiApiKey,
    ) {
        parent::__construct($httpClient);
    }

    public function generateContent(AiRequestInterface $request): AiResponseInterface
    {
        if (!$request instanceof GeminiRequest) {
            throw new \InvalidArgumentException('GeminiClient requires a GeminiRequest instance.');
        }

        return parent::generateContent($request);
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return self::GEMINI_API_ENDPOINT;
    }

    protected function getOptions(array $payload): array
    {
        return [
            'query' => ['key' => $this->geminiApiKey],
            'json'  => $payload,
        ];
    }

    protected function createResponse(array $payload, array $data): AiResponseInterface
    {
        return new GeminiResponse($payload, $data);
    }
}