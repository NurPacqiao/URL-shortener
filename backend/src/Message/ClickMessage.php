<?php

namespace App\Message;

class ClickMessage
{
    private int $urlId;

    public function __construct(int $urlId)
    {
        $this->urlId = $urlId;
    }

    public function getUrlId(): int
    {
        return $this->urlId;
    }
}