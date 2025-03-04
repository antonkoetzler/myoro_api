<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    Log::info('Inspiring quote command executed');
})->purpose('Display an inspiring quote');
