<?php

namespace App\Controller;

use App\Entity\Url;
use App\Message\ClickMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    #[Route('/{shortCode}', name: 'app_redirect')]
    public function index(string $shortCode, EntityManagerInterface $entityManager, MessageBusInterface $bus): Response
    {
        // 1. Find the link
        $repository = $entityManager->getRepository(Url::class);
        $url = $repository->findOneBy(['shortCode' => $shortCode]);

        // --- NEW CHANGE HERE ---
        // Check if link doesn't exist OR if it was "Soft Deleted"
        if (!$url || $url->getDeletedAt() !== null) {
            return $this->json(['error' => 'Link not found or deleted'], 404);
        }
        // -----------------------

        // 2. DISPATCH THE MESSAGE (Async!)
        $bus->dispatch(new ClickMessage($url->getId()));

        // 3. Redirect immediately
        return $this->redirect($url->getOriginalUrl());
    }
}