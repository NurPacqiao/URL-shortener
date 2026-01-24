<?php

namespace App\Tests;

use App\Entity\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    // Test 1: Verify we can set and get data correctly
    public function testUrlEntityStoresData(): void
    {
        $url = new Url();
        $url->setOriginalUrl('https://google.com');
        $url->setShortCode('abc1234');
        $url->setClickCount(10);

        $this->assertEquals('https://google.com', $url->getOriginalUrl());
        $this->assertEquals('abc1234', $url->getShortCode());
        $this->assertEquals(10, $url->getClickCount());
    }

    // Test 2: Verify that a new link is NOT deleted by default
    public function testUrlIsNotDeletedByDefault(): void
    {
        $url = new Url();
        // The deletedAt timestamp should be null when we first create the object
        $this->assertNull($url->getDeletedAt());
    }
}