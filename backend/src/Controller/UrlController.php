<?php

namespace App\Controller;

use App\Entity\Url;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class UrlController extends AbstractController
{
    #[Route('/urls', name: 'create_url', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $originalUrl = $data['url'] ?? null;

        if (!$originalUrl) {
            return $this->json(['error' => 'URL is required'], 400);
        }

        // 1. Generate a random 6-character code
        $shortCode = substr(bin2hex(random_bytes(4)), 0, 6);

        // 2. Save to Database
        $url = new Url();
        $url->setOriginalUrl($originalUrl);
        $url->setShortCode($shortCode);
        $url->setClickCount(0);
        $url->setCreatedAt(new \DateTimeImmutable());
        
        $entityManager->persist($url);
        $entityManager->flush();

        // 3. Return the result (including ID for deletion)
        return $this->json([
            'shortCode' => $shortCode,
            'originalUrl' => $originalUrl,
            'id' => $url->getId()
        ]);
    }

    #[Route('/urls', name: 'list_urls', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Url::class);
        
        // FIX: Only find URLs where 'deletedAt' is NULL
        $urls = $repository->findBy(['deletedAt' => null], ['createdAt' => 'DESC'], 10);

        $data = [];
        foreach ($urls as $url) {
            $data[] = [
                'id' => $url->getId(),
                'shortCode' => $url->getShortCode(),
                'originalUrl' => $url->getOriginalUrl(),
                'clicks' => $url->getClickCount(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/{id}', name: 'app_url_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $repository = $entityManager->getRepository(Url::class);
        $url = $repository->find($id);

        if (!$url) {
            return $this->json(['error' => 'Link not found'], 404);
        }

        // SOFT DELETE: Set the date instead of removing the row
        $url->setDeletedAt(new \DateTimeImmutable());
        $entityManager->flush();

        return $this->json(['message' => 'Deleted successfully']);
    }
}