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

$fileHeaderComment = <<<COMMENT
Copyright (C) 2025 Mazarini <mazarini@pm.me>.
This file is part of mazarini/symfonai.

mazarini/symfonai is free software: you can redistribute it and/or
modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

mazarini/symfonai is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License
along with mazarini/symfonai. If not, see <https://www.gnu.org/licenses/>.
COMMENT;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'var',
        'vendor',
        'bin',
        'public/build',
        'public/bundles',
        'config',
    ])
    ->notPath('public/index.php')
    ->notPath('importmap.php')
    ->notPath('tests/bootstrap.php')
    ->notPath('src/Kernel.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        // --------------------------------------------
        // Standards de base
        // --------------------------------------------
        '@Symfony'       => true,
        '@Symfony:risky' => true,
        '@PSR12'         => true,

        // --------------------------------------------
        // Cohérence stricte avec PHPStan niveau 9
        // --------------------------------------------
        'declare_strict_types' => true,
        'strict_comparison'    => true,
        'strict_param'         => true,
        'mb_str_functions'     => true,

        // --------------------------------------------
        // Syntaxe et lisibilité
        // --------------------------------------------
        'array_syntax'                          => ['syntax' => 'short'],
        'binary_operator_spaces'                => ['default' => 'align_single_space'],
        'cast_spaces'                           => ['space' => 'single'],
        'concat_space'                          => ['spacing' => 'one'],
        'linebreak_after_opening_tag'           => true,
        'trailing_comma_in_multiline'           => ['elements' => ['arrays', 'arguments', 'parameters']],
        'single_quote'                          => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_extra_blank_lines'                  => true,
        'no_superfluous_phpdoc_tags'            => ['allow_mixed' => true],

        // --------------------------------------------
        // Classes & fonctions
        // --------------------------------------------
        'no_php4_constructor'    => true,
        'ordered_class_elements' => ['order' => ['use_trait', 'constant_public', 'property_public', 'method_public']],
        'method_argument_space'  => ['on_multiline' => 'ensure_fully_multiline'],

        // --------------------------------------------
        // Contrôle de flux
        // --------------------------------------------
        'no_useless_else'       => true,
        'no_useless_return'     => true,
        'no_superfluous_elseif' => true,
        'yoda_style'            => false,

        // --------------------------------------------
        // Imports
        // --------------------------------------------
        'ordered_imports'             => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
        'single_import_per_statement' => true,
        'single_line_after_imports'   => true,

        // --------------------------------------------
        // PHPDoc
        // --------------------------------------------
        'phpdoc_align'               => ['align' => 'vertical'],
        'phpdoc_order'               => true,
        'phpdoc_to_comment'          => false,
        'phpdoc_summary'             => false,
        'phpdoc_scalar'              => true,
        'phpdoc_var_without_name'    => false,

        // --------------------------------------------
        // Header
        // --------------------------------------------
        'header_comment' => [
            'header'   => $fileHeaderComment,
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/cache/.php-cs-fixer');
