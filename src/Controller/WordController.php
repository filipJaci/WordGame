<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\WordAPI;

class WordController extends AbstractController
{
    #[Route('/api/word', name: 'app_word', methods: ['POST'])]
    public function index(Request $request): Response
    {
        // Get Word parameter from the request.
        // Instanciate a new WordAPI object with it.
        $word = new WordAPI($request->request->get('word'));
        // Get messages.
        $messages = $word->getMessages();
        // Get http code.
        $code = $word->getHttpStatus();
        // Return response.
        return $this->json([
            'messages' => $messages
        ], $code);
    }
}
