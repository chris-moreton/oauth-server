<?php

namespace App\Http\Controllers;

use Auth;
use Laravel\Passport\Token;
use Illuminate\Http\Response;

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
            return response()->json(['error' => 'Token is not associated with any user and no token details are available.'], Response::HTTP_NOT_FOUND);
        }
    }
    
    public function adminTokenDetails()
    {
        $user = Auth::guard('api')->user();
    
        if ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->token(),
            ];
        } else {
            return response()->json(['error' => 'Token is not associated with any user and no token details are available.'], Response::HTTP_NOT_FOUND);
        }
    }
}
