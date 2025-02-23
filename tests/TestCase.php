<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;

/**
 * Abstract class that must be extended by all tests.
 * TestCase provides each test with shared tools.
 */
abstract class TestCase extends BaseTestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Runs before the test starts.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
        Mockery::globalHelpers();
    }

    /**
     * Runs after tests have executed.
     */
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
