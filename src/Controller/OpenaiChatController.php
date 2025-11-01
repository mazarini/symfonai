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

namespace Mazarini\SymfonAI\Controller;

use Mazarini\SymfonAI\Request\OpenaiRequest;
use Mazarini\SymfonAI\Service\OpenaiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpenaiChatController extends AbstractController
{
    public function __construct(
        private readonly OpenaiClient $openaiClient,
        private readonly string $modelName,
        private readonly ?int $maxTokens = null,
    ) {
    }

    #[Route('/openai-chat', name: 'mazarini_symfonai_openai_chat')]
    public function index(Request $request): Response
    {
        $openaiRequest  = new OpenaiRequest($this->modelName);
        $openaiResponse = null;

        if ($request->isMethod('POST')) {
            $question     = (string) $request->request->get('question', '');
            $systemPrompt = (string) $request->request->get('systemPrompt', '');

            if ('' !== $question) {
                $openaiRequest->setPrompt($question);
                if ('' !== $systemPrompt) {
                    $openaiRequest->setSystemPrompt($systemPrompt);
                }

                if (null !== $this->maxTokens) {
                    $openaiRequest->setMaxTokens($this->maxTokens);
                }

                $openaiResponse = $this->openaiClient->generateContent($openaiRequest);
            }
        }

        return $this->render('openai_chat/index.html.twig', [
            'openaiRequest'  => $openaiRequest,
            'openaiResponse' => $openaiResponse,
        ]);
    }
}
