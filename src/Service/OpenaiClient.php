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
use Mazarini\SymfonAI\Request\OpenaiRequest;
use Mazarini\SymfonAI\Response\AiResponseInterface;
use Mazarini\SymfonAI\Response\OpenaiResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenaiClient extends AbstractAiClient
{
    public function __construct(
        HttpClientInterface $httpClient,
        private readonly string $endpoint,
        private readonly ?string $apiKey = null,
    ) {
        parent::__construct($httpClient);
    }

    public function generateContent(AiRequestInterface $request): AiResponseInterface
    {
        if (!$request instanceof OpenaiRequest) {
            throw new \InvalidArgumentException('OpenaiClient requires a OpenaiRequest instance.');
        }

        return parent::generateContent($request);
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return $this->endpoint;
    }

    protected function getOptions(array $payload): array
    {
        $options = ['json' => $payload];
        if (isset($this->apiKey) && '' !== $this->apiKey) {
            $options['auth_bearer'] = $this->apiKey;
        }

        return $options;
    }

    protected function createResponse(array $payload, array $data): AiResponseInterface
    {
        return new OpenaiResponse($payload, $data);
    }
}