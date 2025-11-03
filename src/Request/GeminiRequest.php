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

namespace Mazarini\SymfonAI\Request;

use Mazarini\SymfonAI\Model\Generation\AbstractConfig;
use Mazarini\SymfonAI\Model\Generation\GeminiConfig;

class GeminiRequest implements AiRequestInterface
{
    private ?array $systemInstruction = null;
    private array $history            = [];
    private ?array $prompt            = null;
    private ?GeminiConfig $config;
    private ?array $safetySettings    = null;
    private ?array $tools             = null;

    public function __construct()
    {
        $this->config = new GeminiConfig();
    }

    public function setPrompt(string $text): self
    {
        $this->prompt = [
            'role'  => 'user',
            'parts' => [['text' => mb_trim($text)]],
        ];

        return $this;
    }

    public function setSystemPrompt(string $text): self
    {
        $trimmedText = mb_trim($text);
        if ('' !== $trimmedText) {
            $this->systemInstruction = [
                'parts' => [['text' => $trimmedText]],
            ];
        }

        return $this;
    }

    public function addHistoryTurn(string $role, string $text): self
    {
        $this->history[] = [
            'role'  => $role,
            'parts' => [['text' => mb_trim($text)]],
        ];

        return $this;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt['parts'][0]['text'] ?? null;
    }

    public function getSystemPrompt(): ?string
    {
        return $this->systemInstruction['parts'][0]['text'] ?? null;
    }

    public function getPayload(): array
    {
        // The prompt is mandatory for a request.
        if (null === $this->prompt) {
            throw new \LogicException('The prompt is mandatory. Use setPrompt() to add it.');
        }

        $payload = [];

        // Add optional system instruction.
        if (null !== $this->systemInstruction) {
            $payload['system_instruction'] = $this->systemInstruction;
        }

        // Build the contents array from history and the current prompt.
        $payload['contents']   = $this->history;
        $payload['contents'][] = $this->prompt;

        // Add other optional configurations.
        if (null !== $this->config) {
            // toArray() returns ['generationConfig' => [...]].
            // We merge this into the payload.
            $payload = array_merge($payload, $this->config->toArray());
        }
        if (null !== $this->safetySettings) {
            $payload['safety_settings'] = $this->safetySettings;
        }
        if (null !== $this->tools) {
            $payload['tools'] = $this->tools;
        }

        return $payload;
    }

    public function getConfig(): ?AbstractConfig
    {
        return $this->config;
    }

    public function setConfig(AbstractConfig $config): self
    {
        if (!$config instanceof GeminiConfig) {
            throw new \InvalidArgumentException('Configuration must be an instance of GeminiConfig.');
        }
        $this->config = $config;

        return $this;
    }
}
