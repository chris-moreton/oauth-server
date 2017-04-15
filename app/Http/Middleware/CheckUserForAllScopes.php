<?php
namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Laravel\Passport\Http\Middleware\CheckScopes as PassportCheckScopes;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

class CheckUserForAllScopes extends PassportCheckScopes
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  array                    $scopes
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next, ...$scopes)
    {
        if (! $request->user() || ! $request->user()->token()) {
            throw new AuthenticationException();
        }
    
        foreach ($scopes as $scope) {
            if (! $request->user()->tokenCan($scope)) {
                throw new AuthorizationException('Invalid scope(s) provided.');
            }
        }
    
        return $next($request);
    }
}
