# Netsensia Authentication Server

An authentication and user management server with oAuth2 support.

You can see the full list of endpoints using Postman by following the link below.

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/641ab76478a4aa5a00f4)

## Installation

	cp .env.example .env
	
Fill in the database details, then install the Composer dependencies.

	composer install
	
A test user will have been created with email chris@example.com and a password of "secret".
	
The oAuth2 server is now configured. You can generate a bearer token with full access using. The token can be used to bypass the need
to perform a password grant or client credentials grant (both of which return bearer tokens themselves).

	php artisan generate-api-token

### Quick Test

	curl --request POST \
	  --url http://oauth-server.laravel/oauth/token \
	  --header 'cache-control: no-cache' \
	  --header 'content-type: application/x-www-form-urlencoded' \
	  --header 'postman-token: 44f5a9c3-d759-030e-afb5-65fd8bb24b91' \
	  --data 'grant_type=password&username=chris@example.com&password=secret&client_id=2&client_secret=CLIENT_SECRET_FOR_CLIENT_2&scope=*'
  
### Importing Old Users

You may want to import users to the users table. It doesn't matter what encryption algorithm was used to store the passwords so long as you tell the oAuth server how the old passwords were encrypted by adding code such as the following to config/auth.php.

    'old-password' => function($value) {
        return md5(env('OLD_PASSWORD_SALT') . $value);
    }
    
The authentication server will then convert the password to the new format following a successful authentication against the old one.
