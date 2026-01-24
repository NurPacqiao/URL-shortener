<?php

namespace App\MessageHandler;

use App\Message\ClickMessage;
use App\Entity\Url;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ClickMessageHandler
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function __invoke(ClickMessage $message)
    {
        // 1. Find the URL by ID
        $url = $this->entityManager->getRepository(Url::class)->find($message->getUrlId());

        if (!$url) {
            return;
        }

        // 2. Update the count
        $url->setClickCount($url->getClickCount() + 1);
        $this->entityManager->flush();
        
        // This runs silently in the background!
    }
}