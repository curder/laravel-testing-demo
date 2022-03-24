<?php

use App\Http\Middleware\RedirectMiddleware;
use App\Models\Redirect;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

// 测试中间件逻辑是否正确
it('the middleware is in isolation', function () {
    $response = (new RedirectMiddleware())->handle(
        createRequest('get', '/'),
        fn () => new Response()
    );
    expect($response->isRedirect(url('/new-homepage')))->toBeFalse(); // 未配置跳转时，访问页面不会跳转

    Redirect::factory()->create([
        'from' => '/',
        'to' => '/new-homepage',
    ]);

    $response = (new RedirectMiddleware())->handle(
        createRequest('get', '/'),
        fn () => new Response()
    );
    expect($response->isRedirect(url('/new-homepage')))->toBeTrue(); // 正确配置了跳转时，访问页面会跳转
});

// 测试中间件能正常工作
it('will preform the right redirects', function () {
    Route::get('my-test-router', fn () => 'ok')->middleware(RedirectMiddleware::class);
    $this->get('/my-test-router')->assertOk();

    Redirect::factory()->create([
        'from' => '/my-test-router',
        'to' => '/new-homepage',
    ]);
    $this->get('/my-test-router')->assertRedirect('/new-homepage');
});
