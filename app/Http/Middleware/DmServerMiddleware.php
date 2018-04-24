<?php

namespace App\Http\Middleware;
use App\Exceptions\InvalidSessionException;
use App\Helpers\DmClient;
use Illuminate\Http\Request;


class DmServerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $next) {
        if (session()->has('username')) {
            return $next($request);
        }
        else {
            throw new InvalidSessionException();
        }
    }
}
