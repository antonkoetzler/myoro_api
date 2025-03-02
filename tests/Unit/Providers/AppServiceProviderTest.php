<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use App\Services\UserService;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    public function testUserServiceBinding(): void
    {
        $this->app->register(AppServiceProvider::class);
        $userService = App::make(UserService::class);
        $this->assertInstanceOf(UserService::class, $userService);
    }
}
