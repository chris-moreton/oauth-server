<?php
namespace App\Extensions;

/**
 * Override the default Passport class so that we can allow superuser scope for client_credentials grant
 * 
 * Although Passport does not allow "*" scope to be granted on client_credentials grant, here we assume
 * that an empty array of scopes is equivalent to "*" scope.
 * 
 */
use Laravel\Passport\Http\Middleware\CheckClientCredentials as PassportCheckClientCredentials;
use Illuminate\Auth\AuthenticationException;

class CheckClientCredentialsForAnyScope extends PassportCheckClientCredentials
{
    /**
     * Validate the scopes on the incoming request.
     *
     * @param  \Psr\Http\Message\ResponseInterface
     * @param  array  $scopes
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function validateScopes($psr, $scopes)
    {
        $tokenScopes = $psr->getAttribute('oauth_scopes');
        
        if (in_array('*', $tokenScopes) || empty($tokenScopes)) {
            return;
        }
    
        foreach ($scopes as $scope) {
            if (in_array($scope, $tokenScopes)) {
                return;
            }
        }
        
        throw new AuthenticationException();
    }
}

