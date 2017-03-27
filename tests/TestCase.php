<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Client;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected $lastParams;
    
    protected function getHeaders($token) {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
    
    protected function getUnauthenticatedJson() {
        return [
            'error' => 'Unauthenticated.'
        ];
    }
    
    protected function routeTest($method, $endpoint, $scopes, $funcParams, $funcHeaders, $funcExpectedJson) {
        
        foreach ($scopes as $scope) {
            $this->json('POST', '/v1/users',
                $this->$funcParams(md5($scope)),
                $this->getHeaders($this->$funcHeaders($scope))
            )
            ->assertJson($this->$funcExpectedJson());
        }
    }
        
    protected function getClientCredentialsToken($scopes) {
        $personalAccessClient = Client::where('personal_access_client', 1)->first();
    
        $result = $this->json('POST', '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $personalAccessClient->id,
            'client_secret' => $personalAccessClient->secret,
            'scope' => $scopes,
        ]);
    
        $result->assertJson(['token_type' => 'Bearer']);
    
        return $result->json()['access_token'];
    }
    
    protected function getUserCredentialsToken($username, $password, $scopes) {
        $passwordGrantClient = Client::where('personal_access_client', 0)->first();
    
        $result = $this->json('POST', '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $passwordGrantClient->id,
            'client_secret' => $passwordGrantClient->secret,
            'username' => $username,
            'password' => $password,
            'scope' => $scopes,
        ]);
    
        $result->assertJson(['token_type' => 'Bearer']);
    
        return $result->json()['access_token'];
    }
    
    protected function getDefaultUserCredentialsToken($scopes) {
        return $this->getUserCredentialsToken('chris@example.com', 'secret', $scopes);
    }
}
