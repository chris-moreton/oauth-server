# Netsensia Authentication Server

An authentication server with oAuth2 support.
 
## Installation

	cp .env.example .env
	
Fill in the database details, then install the Composer dependencies.

	composer install
	
Generate the application key

    php artisan key:generate

Create the database tables

	php artisan migrate
	
Generate the personal access and passport clients in the database

	php artisan passport:install
	
Generate test user (optional)

This will create a test user called chris@example.com with a password of "secret".

	php artisan db:seed
	
The oAuth2 server is now configured. You can generate a bearer token with full access using. The token can be used to bypass the need
to perform a password grant or client credentials grant (both of which return bearer tokens themselves).

	php artisan generate-api-token
	
The full documentation for the server can be found at 

https://documenter.getpostman.com/view/133903/netsensia-oauth2-server/6YsWxPw

## Quick docs

### Importing Old Users

You may want to import users to the users table. It doesn't matter what encryption algorithm was used to store the passwords so long as you tell the oAuth server how the old passwords were encrypted by adding code such as the following to config/auth.php.

    'old-password' => function($value) {
        return md5(env('OLD_PASSWORD_SALT') . $value);
    }
    
The authentication server will then convert the password to the new format following a successful authentication against the old one.
