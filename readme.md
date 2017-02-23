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
	
Generate test users (optional)

	php artisan db:seed
	
The oAuth2 server is now configured. You can generate a bearer token with full access using. The token can be used to bypass the need
to perform a password grant or client credentials grant (both of which return bearer tokens themselves).

	php artisan generate-api-token
	
The full documentation for the server can be found at 

https://documenter.getpostman.com/view/133903/netsensia-oauth2-server/6YsWxPw

## Quick docs

### Importing Old Users

You may add users directly to the users table including using their passwords encrypted using a legacy encryption algorithm. The authentication server will convert the password to the new
format following a successfull authentication against the old one. You can tell the oAuth server how the old passwords were encrypted by adding code such as the following to config/auth.php

	'old-password' => function($value) {
        return md5(env('OLD_PASSWORD_SALT') . $value);
    }
 
### Password Grant

    curl --request POST \
      --url {{url}}/oauth/token \
      --header 'content-type: multipart/form-data; boundary=---011000010111000001101001' \
      --form grant_type=password \
      --form 'username={{username}}' \
      --form 'password={{password}}' \
      --form client_id=2 \
      --form 'client_secret={{password_grant_client_secret}}' \
      --form 'scope=*'

### Client Credentials Grant

    curl --request POST \
      --url {{url}}/oauth/token \
      --header 'content-type: multipart/form-data; boundary=---011000010111000001101001' \
      --form grant_type=client_credentials \
      --form client_id=1 \
      --form 'client_secret={{client_credentials_client_secret}}' \
      --form 'scope=*'
      
### Password Check

	curl --request POST \
	  --url {{url}}/v1/users/{{username}}/passwordcheck \
	  --header 'authorization: Bearer {{token}}' \
	  --header 'content-type: application/json' \
	  --data '{"password":"{{password}}"}'
	  
### Create User

	curl --request POST \
	  --url {{url}}/v1/users \
	  --header 'content-type: application/json' \
	  --data '{\n  "name": "{{name}}",\n  "email": "{{email}}",\n  "password": "{{password}}"\n}'
	  
### Get User Details

	curl --request GET \
	  --url {{url}}/v1/users/{{userid|email}}
	  
### Update User

	curl --request PUT \
	  --url {{url}}/v1/users/{{userid}} \
	  --header 'authorization: Bearer {{token}}' \
	  --header 'content-type: application/json' \
	  --data '{"remember_token":"{{remember_token}}"}'
	  
