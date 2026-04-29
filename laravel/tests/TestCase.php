<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * PHPUnit fails a test with "did not close its own output buffers" when the
     * tested code leaves extra output buffering levels open. We track the level
     * at setUp and clean anything above it at tearDown to keep the suite stable.
     */
    protected int $initialOutputBufferLevel = 0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->initialOutputBufferLevel = ob_get_level();
    }

    protected function tearDown(): void
    {
        while (ob_get_level() > $this->initialOutputBufferLevel) {
            @ob_end_clean();
        }

        parent::tearDown();
    }
}
