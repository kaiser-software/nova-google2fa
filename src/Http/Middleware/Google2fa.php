<?php

namespace Lifeonscreen\Google2fa\Http\Middleware;

use Closure;
use Lifeonscreen\Google2fa\Google2FAAuthenticator;
use PragmaRX\Google2FA\Google2FA as G2fa;
use PragmaRX\Recovery\Recovery;
use Lifeonscreen\Google2fa\Google2fa as Google2faTool;

/**
 * Class Google2fa
 * @package Lifeonscreen\Google2fa\Http\Middleware
 */
class Google2fa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \PragmaRX\Google2FA\Exceptions\InsecureCallException
     */
    public function handle($request, Closure $next)
    {
        if (!config('lifeonscreen2fa.enabled')) {
            return $next($request);
        }
        if ($request->path() === 'nova/los/2fa/confirm' || $request->path() === 'nova/los/2fa/authenticate'
            || $request->path() === 'nova/los/2fa/register') {
            return $next($request);
        }
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);
        if (auth()->guest() || $authenticator->isAuthenticated()) {
            return $next($request);
        }
        if (empty(auth()->user()->user2fa) || auth()->user()->user2fa->google2fa_enable === 0) {
            $google2faTool = new Google2faTool();
            return $google2faTool->showRecoveryView();
        }

        return response(view('google2fa::authenticate'));
    }
}