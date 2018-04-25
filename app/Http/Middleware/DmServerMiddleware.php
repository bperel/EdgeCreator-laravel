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
            /** @var DmClient $dmClient */
            $dmClient = resolve(DmClient::class);
            $dmClient->setUserCredentials(
                session()->get('username'),
                session()->get('password')
            );
            return $next($request);
        }
        else {
            throw new InvalidSessionException();
        }
    }
}
