<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $cacheFile = __DIR__.'/../bootstrap/cache/config.php';
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }

        parent::setUp();
    }
}
