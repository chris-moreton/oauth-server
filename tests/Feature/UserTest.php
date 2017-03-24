<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Client;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $name = time();
        $email = $name . '@netsensia.com';
        
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => 'asdasd',
        ];
        
        $headers = [
            'Authorization' => 'Bearer ' . time(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        
        $this->json('POST', '/v1/users', $params, $headers)->assertJson([
           'error' => 'Unauthenticated.'
        ]);
            
        $headers['Authorization'] = 'Bearer ' . $this->getClientCredentialsToken();
        
        $this->json('POST', '/v1/users', $params, $headers)->assertJson([
            'name' => $name,
            'email' => $email,
        ]);
        
    }
    
    /**
     * Get a token from a client_credentials grant
     * 
     * @return string
     */
    private function getClientCredentialsToken() {
        $personalAccessClient = Client::where('personal_access_client', 1)->first();
        
        $result = $this->json('POST', '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $personalAccessClient->id,
            'client_secret' => $personalAccessClient->secret,
            'scope' => '*',
        ]);
        
        $result->assertJson(['token_type' => 'Bearer']);
        
        return $result->json()['access_token'];
    }
}