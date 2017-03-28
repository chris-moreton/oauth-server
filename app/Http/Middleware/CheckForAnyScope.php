<?php
namespace App\Http\Middleware;

use Laravel\Passport\Exceptions\MissingScopeException;
use Illuminate\Http\Response;
use Laravel\Passport\Http\Middleware\CheckForAnyScope as PassportCheckForAnyScope;
use Illuminate\Auth\Access\AuthorizationException;

class CheckForAnyScope extends PassportCheckForAnyScope
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
            throw new AuthorizationException('Invalid scope(s) provided.');
        }
    }
}
