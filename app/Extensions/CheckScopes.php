<?php

namespace App\Extensions;

use Laravel\Passport\Exceptions\MissingScopeException;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Middleware\CheckScopes as PassportCheckScopes;
use Illuminate\Auth\Access\AuthorizationException;

class CheckScopes extends PassportCheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  $scopes
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next, ...$scopes)
    {
        try {
            return parent::handle($request, $next, ...$scopes);
        } catch (MissingScopeException $e) {
            throw new AuthorizationException('invalid scope(s) provided.');
        }
    }
}
