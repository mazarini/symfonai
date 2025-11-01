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

class OpenaiRequest
{
    private string $model;
    private ?array $systemPrompt = null;
    private array $history       = [];
    private ?array $prompt       = null;
    private array $options       = [];

    public function __construct(string $model = 'gpt-4', float $temperature = 0.7)
    {
        $this->model                     = $model;
        $this->options['temperature'] = $temperature;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
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

    public function setTemperature(float $value): self
    {
        if ($value < 0.0 || $value > 2.0) {
            throw new \InvalidArgumentException('Temperature must be between 0.0 and 2.0.');
        }
        $this->options['temperature'] = $value;

        return $this;
    }

    public function setMaxTokens(int $value): self
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Max tokens must be a positive integer.');
        }
        $this->options['max_tokens'] = $value;

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

        return array_merge(
            [
                'model'    => $this->model,
                'messages' => $messages,
            ],
            $this->options
        );
    }
}
