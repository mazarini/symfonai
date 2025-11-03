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
use Mazarini\SymfonAI\Model\Generation\OpenaiConfig;

class OpenaiRequest implements AiRequestInterface
{
    private ?array $systemPrompt = null;
    private array $history       = [];
    private ?array $prompt       = null;
    private ?OpenaiConfig $config;

    public function __construct(string $defaultModel = 'gpt-4')
    {
        $this->config = new OpenaiConfig(model: $defaultModel);
    }

    public function setSystemPrompt(string $text): self
    {
        $trimmedText = mb_trim($text);
        if ('' !== $trimmedText) {
            $this->systemPrompt = ['role' => 'system', 'content' => $trimmedText];
        }

        return $this;
    }

    public function setPrompt(string $text): self
    {
        $this->prompt = ['role' => 'user', 'content' => mb_trim($text)];

        return $this;
    }

    public function addHistoryTurn(string $role, string $text): self
    {
        if (!\in_array($role, ['user', 'assistant'])) {
            throw new \InvalidArgumentException('Invalid role for history. Must be "user" or "assistant".');
        }
        $this->history[] = ['role' => $role, 'content' => mb_trim($text)];

        return $this;
    }

    public function getSystemPrompt(): ?string
    {
        return $this->systemPrompt['content'] ?? null;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt['content'] ?? null;
    }

    public function getPayload(): array
    {
        if (null === $this->prompt) {
            throw new \LogicException('The prompt is mandatory. Use setPrompt() to add it.');
        }

        $messages = [];
        if (null !== $this->systemPrompt) {
            $messages[] = $this->systemPrompt;
        }
        $messages = array_merge($messages, $this->history);
        $messages[] = $this->prompt;

        $payload = [
            'messages' => $messages,
        ];

        if (null !== $this->config) {
            $payload = array_merge($payload, $this->config->toArray());
        }

        return $payload;
    }

    public function getConfig(): ?AbstractConfig
    {
        return $this->config;
    }

    public function setConfig(AbstractConfig $config): self
    {
        if (!$config instanceof OpenaiConfig) {
            throw new \InvalidArgumentException('Configuration must be an instance of OpenaiConfig.');
        }
        $this->config = $config;

        return $this;
    }
}
