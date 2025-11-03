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

namespace Mazarini\SymfonAI\Response;

use Mazarini\SymfonAI\Response\AiResponseInterface;

class OpenaiResponse implements AiResponseInterface
{
    private array $requestPayload;
    private array $rawResponse;

    private ?string $answer        = null;
    private ?array $usageMetadata  = null;
    private bool $isError       = false;
    private ?string $errorMessage = null;

    public function __construct(array $requestPayload, array $rawApiResponse)
    {
        $this->requestPayload = $requestPayload;
        $this->rawResponse    = $rawApiResponse;
        $this->parseResponse();
    }

    private function parseResponse(): void
    {
        if (isset($this->rawResponse['error'])) {
            $this->isError      = true;
            $this->errorMessage = $this->rawResponse['error']['message'] ?? 'An unknown error occurred.';
            $this->answer       = 'Error: ' . $this->errorMessage;

            return;
        }

        $finishReason = $this->rawResponse['choices'][0]['finish_reason'] ?? 'UNKNOWN';

        if ('stop' !== $finishReason) {
            $this->isError      = true;
            $this->errorMessage = sprintf('The API response was stopped for a non-standard reason: %s.', $finishReason);
            $this->answer       = 'Error: ' . $this->errorMessage;
            // Let's also grab any partial text that might exist.
            $this->answer        .= ' ' . ($this->rawResponse['choices'][0]['message']['content'] ?? '');
            $this->usageMetadata = $this->rawResponse['usage'] ?? null;

            return;
        }

        $text = $this->rawResponse['choices'][0]['message']['content'] ?? null;

        if (null !== $text && '' !== $text) {
            $this->answer = $text;
        } else {
            $this->isError      = true;
            $this->errorMessage = 'Received an empty or invalid response from the API, even though finish_reason was stop.';
            $this->answer       = 'Error: ' . $this->errorMessage;
        }

        $this->usageMetadata = $this->rawResponse['usage'] ?? null;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function getUsageMetadata(): ?array
    {
        return $this->usageMetadata;
    }

    public function isError(): bool
    {
        return $this->isError;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getRequestPayload(): array
    {
        return $this->requestPayload;
    }

    public function getRawResponse(): array
    {
        return $this->rawResponse;
    }
}
