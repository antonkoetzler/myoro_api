<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Abstract class that must be extended by all tests.
 * TestCase provides each test with shared tools.
 */
abstract class TestCase extends BaseTestCase
{
    use WithFaker;

    /**
     * Runs before the test starts.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }
}
