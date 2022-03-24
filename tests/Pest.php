<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\CreatesApplication;

uses(TestCase::class, CreatesApplication::class, LazilyRefreshDatabase::class)->in('Unit', 'Feature');

function createRequest($method, $uri): Request
{
    $symfony_request = SymfonyRequest::create(
        $uri,
        $method,
    );

    return Request::createFromBase($symfony_request);
}
