<?php
namespace App\Extensions;

use App\User;

class TransitionalHasher extends \Illuminate\Hashing\BcryptHasher {

    public function check($value, $hashedValue, array $options = array())
    {
        // If check fails, is it an old MD5 hash?
        if ( !password_verify($value, $hashedValue) )
        {
            $oldPasswordHash = config('auth.old-password')($value);
            
            if ($hashedValue == $oldPasswordHash) {
                // The user password was encrypted using the old method
                
                $user = User::where( 'password', $oldPasswordHash )->first();
    
                if ($user)  // We found a user with a matching MD5 hash
                {
                    // Update the password to Laravel's Bcrypt hash
                    // If two users have matching passwords, we might update the
                    // wrong user -- but it doesn't matter!
                    $user->password = \Hash::make($value);
                    $user->save();
    
                }
                
                // Log in the user
                return true;
            }
        }

        return password_verify($value, $hashedValue);
    }

}
