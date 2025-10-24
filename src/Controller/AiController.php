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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AiController extends AbstractController
{
    #[Route('/chat', name: 'symfonai_chat')]
    public function index(Request $request): Response
    {
        $question = mb_trim((string) $request->request->get('question', ''));
        $answer   = null;

        if ($question !== '') {
            // 🧠 Simulation temporaire (on branchera l’IA plus tard)
            $answer = \sprintf('Réponse simulée pour : "%s"', $question);
        }

        return $this->render('chat/index.html.twig', [
            'question' => $question,
            'answer'   => $answer,
        ]);
    }
}
