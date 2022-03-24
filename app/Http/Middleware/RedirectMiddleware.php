<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = parse_url($request->fullUrl(), PHP_URL_PATH) ?? '/';

        $redirect = Redirect::query()->where('from', $path)->first();

        if (! $redirect) {
            return $next($request);
        }

        return response()->redirectTo($redirect->to);
    }
}
