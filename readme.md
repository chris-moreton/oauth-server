# oAuth2 Server

## Installation

	cp .env.example .env
	
Fill in the database details, then install the Composer dependencies.

	composer install

Create the database tables

	php artisan migrate
	
Generate the personal access and passport clients in the database

	php artisan passport:install
	
Generate test users

	php artisan db:seed
	
The oAuth2 server is now configured. You can generate a bearer token with full access using:

	php artisan generate-api-token
	
The documentation for the server can be found at https://documenter.getpostman.com/view/133903/netsensia-oauth2-server/6YsWxPw

	