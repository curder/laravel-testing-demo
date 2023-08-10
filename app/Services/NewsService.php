<?php

namespace App\Services;

use App\Exceptions\NewsRequestException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Throwable;

/**
 * 在 Laravel 中使用 Http 客户端测试 api 请求
 *
 * @see https://laravel-news.com/laravel-http-client-tips
 * @see https://github.com/curder/laravel-test-demo/tree/news-api
 */
class NewsService
{
    public function __construct(
        private PendingRequest $client
    ) {
    }

    /**
     * @throws NewsRequestException
     * @throws Throwable
     */
    public function headlines(array $query = []): Collection
    {
        try {
            $response = $this->client->get(
                '/v2/top-headlines',
                array_merge(['country' => 'us'], $query)
            );
        } catch (Throwable $e) {
            throw new NewsRequestException(message: $e->getMessage());
        }

//        throw_if(
//            $response->failed() && $response->json('status') !== 'ok',
//            new NewsRequestException()
//        );

        $data = $response->json();

        return collect($data)->mapWithKeys(function ($item, $key) {
            if ($key === 'articles') {
                return [
                    $key => collect($item)->map(fn (array $news) => [
                        'author' => $news['author'],
                        'title' => $news['title'],
                        'url' => $news['url'],
                        'image_url' => $news['urlToImage'],
                        'published_at' => $news['publishedAt'] ? Carbon::parse($news['publishedAt']) : 'unknown',
                    ]),
                ];
            }

            return [$key => $item];
        });
    }
}
