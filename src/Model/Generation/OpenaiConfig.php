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

namespace Mazarini\SymfonAI\Model\Generation;

/**
 * Represents the generation configuration for OpenAI-compatible APIs.
 *
 * @see https://platform.openai.com/docs/api-reference/chat/create
 */
class OpenaiConfig extends AbstractConfig
{
    /**
     * @param string|null $model  the model to use for the completion
     * @param int|null    $seed   a seed for deterministic sampling
     * @param bool|null   $stream whether to stream the response
     */
    public function __construct(
        // OpenAI-specific parameters
        public ?string $model = null,
        public ?int $seed = null,
        public ?bool $stream = null,
    ) {
        parent::__construct();
    }

    public function toArray(): array
    {

        $data = [
            'temperature'       => $this->temperature,
            'top_p'             => $this->topP,
            'max_tokens'        => $this->maxTokens,
            'stop'              => $this->stopSequences,
            'frequency_penalty' => $this->frequencyPenalty,
            'presence_penalty'  => $this->presencePenalty,
            'n'                 => $this->candidateCount,
            'model'             => $this->model,
            'seed'              => $this->seed,
            'stream'            => $this->stream,
        ];

        // Filter out null properties to avoid sending them in the API request

        return array_filter($data, fn ($value) => $value !== null);

    }
}
