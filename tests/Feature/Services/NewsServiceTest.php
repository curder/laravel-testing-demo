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

    expect(Arr::get($response, 'articles.0.published_at'))
        ->toBeInstanceOf(Carbon::class)
        ->and(Arr::get($response, 'articles.0.author'))->toContain('The Athletic Staff')
        ->and(count($response['articles']))->toEqual(20)
        ->and($response)->toHaveKey('status')
        ->and($response)->toHaveKey('totalResults');
});

it('will throw exception when request failed', function () {
    Http::fakeSequence()->pushStatus(Response::HTTP_UNAUTHORIZED);
    expect(fn () => app(NewsService::class)->headlines())->toThrow(NewsRequestException::class);
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
