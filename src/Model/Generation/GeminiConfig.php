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
 * Represents the generation configuration for the Gemini API.
 *
 * @see https://ai.google.dev/docs/api/rest/v1beta/models/generateContent#generationconfig
 */
class GeminiConfig extends AbstractConfig
{
    /**
     * @param string|null $responseMimeType the desired MIME type of the output
     */
    public function __construct(
        // Gemini-specific parameters
        public ?string $responseMimeType = null,
    ) {
        parent::__construct();
    }

    public function toArray(): array
    {

        $configData = [
            'temperature'      => $this->temperature,
            'topP'             => $this->topP,
            'maxOutputTokens'  => $this->maxTokens,
            'stopSequences'    => $this->stopSequences,
            'frequencyPenalty' => $this->frequencyPenalty,
            'presencePenalty'  => $this->presencePenalty,
            'candidateCount'   => $this->candidateCount,
            'responseMimeType' => $this->responseMimeType,
        ];

        // Filter out null properties to avoid sending them in the API request

        $filteredConfigData = array_filter($configData, fn ($value) => $value !== null);

        return ['generationConfig' => $filteredConfigData];

    }
}
