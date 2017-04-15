<?php

namespace App\Http\Controllers;

use Auth;
use App;
use Laravel\Passport\Token;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use League\OAuth2\Server\ResourceServer;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Passport;

class TokenController extends Controller
{
    /**
     * Get the user details and scopes for the given token
     *
     * @return \Illuminate\Http\Response
     */
    public function userTokenDetails()
    {
        $user = Auth::guard('api')->user();
        
        if ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->token(),
            ];
        } else {
            return response()->json(
                ['error' => 'Token is not associated with any user and no token details are available.'],
                Response::HTTP_NOT_FOUND
            );
        }
    }
    
    public function adminTokenDetails(Request $request)
    {
        $psr = (new DiactorosFactory)->createRequest($request);
        
        $server = new ResourceServer(
            App::make(AccessTokenRepository::class),
            'file://'.Passport::keyPath('oauth-public.key')
        );
        
        $psr = $server->validateAuthenticatedRequest($psr);
        
        $scopes = $psr->getAttribute('oauth_scopes');
        
        return [
            'scopes' => $scopes
        ];
    }
}
