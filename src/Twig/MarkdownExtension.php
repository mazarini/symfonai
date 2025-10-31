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

namespace Mazarini\SymfonAI\Twig;

use ParsedownExtra;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MarkdownExtension extends AbstractExtension
{
    private ParsedownExtra $parsedown;

    public function __construct()
    {
        $this->parsedown = new ParsedownExtra();
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('markdown', [$this, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param ?string $markdown
     */
    public function parseMarkdown(?string $markdown): string
    {
        if (null === $markdown) {
            return '';
        }

        return $this->parsedown->text($markdown);
    }
}
