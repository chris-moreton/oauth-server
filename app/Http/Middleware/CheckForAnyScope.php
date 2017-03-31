<?php
namespace App\Http\Middleware;

/**
 * Override the default Passport class so that we can allow superuser scope for client_credentials grant
 * 
 * Although Passport does not allow "*" scope to be granted on client_credentials grant, here we assume
 * that an empty array of scopes is equivalent to "*" scope.
 * 
 */
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;

class CheckForAnyScope
{
    /**
     * The Resource Server instance.
     *
     * @var ResourceServer
     */
    private $server;
    
    /**
     * Create a new middleware instance.
     *
     * @param  ResourceServer  $server
     * @return void
     */
    public function __construct(ResourceServer $server)
    {
        $this->server = $server;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$scopes)
    {
        $psr = (new DiactorosFactory)->createRequest($request);
    
        try{
            $psr = $this->server->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }
    
        $this->validateScopes($psr, $scopes);
    
        return $next($request);
    }
    
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
        
        throw new AuthorizationException('Invalid scope(s) provided.');
    }
}

