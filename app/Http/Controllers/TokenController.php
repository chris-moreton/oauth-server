<?php

namespace App\Http\Controllers;

use Auth;
use Laravel\Passport\Token;

class TokenController extends Controller
{
    /**
     * Get the user details and scopes for the given token
     *
     * @return \Illuminate\Http\Response
     */
    public function tokenDetails()
    {
        $user = Auth::guard('api')->user();
        
        if ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->token(),
            ];
        } else {
            return null;
        }
    }
}
