<?php

use App\Console\Commands\ReloadCache;
use Illuminate\Support\Sleep;

it(description: 'has app:reload-cache command')->expect(class_exists(ReloadCache::class))->toBeTrue();

it(description: 'can reload app cache')
    ->defer(function () {
        Sleep::fake();
    })
    ->artisan(command: 'app:reload-cache')
    ->expectsOutputToContain(string: 'Blade templates cached successfully.')
    ->expectsOutputToContain(string: 'Routes cached successfully.')
    ->expectsOutputToContain(string: 'Caching the framework bootstrap files. ')
    ->expectsOutputToContain(string: 'Events cached successfully.')
    ->expectsOutput(output: 'Successfully reload caches.')
    ->assertExitCode(exitCode: 0);
