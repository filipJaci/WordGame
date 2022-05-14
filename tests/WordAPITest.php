<?php

namespace App\Tests;

use App\Entity\WordAPI;
use PHPUnit\Framework\TestCase;

class WordAPITest extends TestCase
{
    /** @test */
    public function a_connection_can_be_established_to_the_word_api(): void
    {
        $testResult = WordAPI::testConnection();
        $this->assertTrue($testResult);
    }
}
