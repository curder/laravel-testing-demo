[![Test Laravel Github action](https://github.com/curder/laravel-testing-demo/actions/workflows/run-test.yml/badge.svg?branch=http)](https://github.com/curder/laravel-testing-demo/actions/workflows/run-test.yml)
[![PHPStan](https://github.com/curder/laravel-testing-demo/actions/workflows/phpstan.yml/badge.svg?branch=http)](https://github.com/curder/laravel-testing-demo/actions/workflows/phpstan.yml)
[![Check & fix styling](https://github.com/curder/laravel-testing-demo/actions/workflows/php-cs-fixer.yml/badge.svg?branch=http)](https://github.com/curder/laravel-testing-demo/actions/workflows/php-cs-fixer.yml)


# HTTP 网络请求

```php
<?php

use App\Exceptions\NewsRequestException;
use App\Services\NewsService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

it('can fetch top headlines news', closure: function () {
    Http::fakeSequence()->pushFile(__DIR__.'/stubs/headlines.json');

    $response = app(NewsService::class)->headlines();

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('X-Api-Key') &&
            $request->url() === config('services.news.base_url').'/v2/top-headlines?country=us';
    });

    $this->assertInstanceOf(Carbon::class, Arr::get($response, 'articles.0.published_at'));
    $this->assertEquals('The Athletic Staff', Arr::get($response, 'articles.0.author'));
    $this->assertCount(20, $response['articles']);
    $this->assertArrayHasKey('status', $response);
    $this->assertArrayHasKey('totalResults', $response);
    $this->assertArrayHasKey('articles', $response);
});

it('will throw exception when request failed', function () {
    Http::fakeSequence()->pushStatus(Response::HTTP_UNAUTHORIZED);

    $this->expectException(NewsRequestException::class);
    app(NewsService::class)->headlines();
});

it('will retry up to three times', function () {
    Http::fakeSequence()
        ->pushStatus(Response::HTTP_NOT_FOUND)
        ->pushStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->pushFile(__DIR__.'/stubs/headlines.json');

    $this->withoutExceptionHandling();
    app(NewsService::class)->headlines();
    Http::assertSentCount(config('services.news.retry'));
});
```
