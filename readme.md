# Netsensia Authentication Server

A ready-to-go PHP/MySQL implementation of an authentication and user management server with oAuth2 support.

A [PHP Client](https://github.com/chris-moreton/oauth-server-php-client) is also maintained as part of this project, which includes the test suite used to test both the client and this API. 

It is built with [Laravel Passport](https://github.com/laravel/passport), which is a Laravel implementation of [The PHP League oAuth2 Server](https://github.com/thephpleague/oauth2-server)

You can see the full list of endpoints using Postman by following the link below.

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/4a81ed8ddf3476d5eb91)

## Installation

	cp .env.example .env
	
Fill in the database details, then install the Composer dependencies.

	composer install
	
You will see the client credentials and password grant ids and secrets output to the console. Also, a test user will have been created with email chris@example.com and a password of "secret".
	
The oAuth2 server is now configured. You can generate a bearer token with full access using. The token can be used to bypass the need
to perform a password grant or client credentials grant (both of which return bearer tokens themselves).

	php artisan generate-api-token
  
### Importing Old Users

You may want to import users to the users table. It doesn't matter what encryption algorithm was used to store the passwords so long as you tell the oAuth server how the old passwords were encrypted by adding code such as the following to config/auth.php.

    'old-password' => function($value) {
        return md5(env('OLD_PASSWORD_SALT') . $value);
    }
    
The authentication server will then convert the password to the new format following a successful authentication against the old one.
