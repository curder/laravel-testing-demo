<?php

namespace App\Providers;

use App\Services\NewsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NewsService::class, function () {
            $http = Http::withHeaders(
                ['X-Api-Key' => config('services.news.api_token')]
            )->withOptions([
                'base_uri' => config('services.news.base_url'),
                'timeout' => config('services.news.timeout', 10),
                'connect_timeout' => config('services.news.connect_timeout', 2),
            ])->retry(config('services.news.retry'));

            return new NewsService($http);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
