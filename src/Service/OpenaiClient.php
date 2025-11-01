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

use Mazarini\SymfonAI\Request\OpenaiRequest;
use Mazarini\SymfonAI\Response\OpenaiResponse;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenaiClient
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $endpoint,
        private readonly ?string $apiKey = null,
    ) {
    }

    public function generateContent(OpenaiRequest $request): OpenaiResponse
    {
        $payload = $request->getPayload();

        $options = ['json' => $payload];
        if (!empty($this->apiKey)) {
            $options['auth_bearer'] = $this->apiKey;
        }

        try {
            $response = $this->httpClient->request('POST', $this->endpoint, $options);

            $data = $response->toArray();

            return new OpenaiResponse($payload, $data);
        } catch (ClientException $e) {
            return $this->handleClientException($e, $payload);
        } catch (TransportExceptionInterface $e) {
            return $this->handleTransportException($e, $payload);
        } catch (\Throwable $e) {
            return $this->handleGenericException($e, $payload);
        }
    }

    private function handleClientException(ClientException $e, array $payload): OpenaiResponse
    {
        $response   = $e->getResponse();
        $statusCode = $response->getStatusCode();
        $rawContent = $response->getContent(false);

        try {
            $errorData = $response->toArray(false);
        } catch (DecodingExceptionInterface $decodingException) {
            $errorData = [
                'error' => [
                    'code'    => $statusCode,
                    'message' => 'Could not decode error response: ' . $decodingException->getMessage(),
                    'status'  => 'DECODING_FAILED',
                    'details' => $rawContent,
                ],
            ];
        }
        if (!isset($errorData['error']['message'])) {
            $errorData['error']['message'] = 'An unknown client error occurred.';
        }
        $errorData['error']['message'] = sprintf('[%d] %s', $statusCode, $errorData['error']['message']);

        return new OpenaiResponse($payload, $errorData);
    }

    private function handleTransportException(TransportExceptionInterface $e, array $payload): OpenaiResponse
    {
        $errorData = [
            'error' => [
                'code'    => $e->getCode(),
                'message' => 'A transport error occurred: ' . $e->getMessage(),
                'status'  => 'TRANSPORT_ERROR',
            ],
        ];

        return new OpenaiResponse($payload, $errorData);
    }

    private function handleGenericException(\Throwable $e, array $payload): OpenaiResponse
    {
        $errorData = [
            'error' => [
                'code'    => $e->getCode(),
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'status'  => 'UNEXPECTED_ERROR',
            ],
        ];

        return new OpenaiResponse($payload, $errorData);
    }
}
