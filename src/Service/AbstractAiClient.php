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
use Mazarini\SymfonAI\Response\AiResponseInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractAiClient
{
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
    ) {
    }

    public function generateContent(AiRequestInterface $request): AiResponseInterface
    {
        $payload = $request->getPayload();

        try {
            $response = $this->httpClient->request($this->getMethod(), $this->getEndpoint(), $this->getOptions($payload));
            $data     = $response->toArray();

            return $this->createResponse($payload, $data);
        } catch (ClientException $e) {
            return $this->handleClientException($e, $payload);
        } catch (TransportExceptionInterface $e) {
            return $this->handleTransportException($e, $payload);
        } catch (\Throwable $e) {
            return $this->handleGenericException($e, $payload);
        }
    }

    abstract protected function getMethod(): string;

    abstract protected function getEndpoint(): string;

    abstract protected function getOptions(array $payload): array;

    abstract protected function createResponse(array $payload, array $data): AiResponseInterface;

    protected function handleClientException(ClientException $e, array $payload): AiResponseInterface
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
        $errorData['error']['message'] = \sprintf('[%d] %s', $statusCode, $errorData['error']['message']);

        return $this->createResponse($payload, $errorData);
    }

    protected function handleTransportException(TransportExceptionInterface $e, array $payload): AiResponseInterface
    {
        $errorData = [
            'error' => [
                'code'    => $e->getCode(),
                'message' => 'A transport error occurred: ' . $e->getMessage(),
                'status'  => 'TRANSPORT_ERROR',
            ],
        ];

        return $this->createResponse($payload, $errorData);
    }

    protected function handleGenericException(\Throwable $e, array $payload): AiResponseInterface
    {
        $errorData = [
            'error' => [
                'code'    => $e->getCode(),
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'status'  => 'UNEXPECTED_ERROR',
            ],
        ];

        return $this->createResponse($payload, $errorData);
    }
}
