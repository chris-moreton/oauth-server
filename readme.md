# oAuth2 Server

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
	
Generate test users

	php artisan db:seed
	
The oAuth2 server is now configured. You can generate a bearer token with full access using:

	php artisan generate-api-token
	
The full documentation for the server can be found at 

    https://documenter.getpostman.com/view/133903/netsensia-oauth2-server/6YsWxPw
    
## Quick docs

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