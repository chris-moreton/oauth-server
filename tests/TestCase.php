<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Client;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected $lastParams;

    protected function getPostHeaders() {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ];
    }
    
    protected function getAuthorizationHeaders($token) {
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

    protected function getWrongScopesJson() {
        return [
            'error' => 'Invalid scope(s) provided.'
        ];
    }
    
    protected function getInvalidScopesJson() {
        return [
            'error' => 'invalid_scope',  
        ];
    }
    
    protected function getWrongUserJson() {
        return [
            'error' => 'Token does not belong to requested user.'
        ];
    }
    
    protected function getNoUserJson() {
        return [
            'error' => 'Token is not associated with any user and no token details are available.',
        ];
    }
    
    protected function getInvalidCredentialsJson() {
        return [
            'error' => 'invalid_credentials',
        ];
    }
    
    protected function getBearerTokenJson() {
        return [
            'token_type' => 'Bearer',  
        ];
    }
    
    protected function getUserNotFoundJson() {
        return [
            'error' => 'User not found.',
        ];
    }
    
    protected function routeTest($method, $endpoint, $scopes, $funcParams, $funcHeaders, $funcExpectedJson, $expectedStatusCode) {
        
        foreach ($scopes as $scope) {
            $response = $this->json('POST', '/v1/users',
                $this->$funcParams(md5($scope)),
                $this->getAuthorizationHeaders($this->$funcHeaders($scope))
            );
            
            $response->assertStatus($expectedStatusCode);
            $response->assertJson($this->$funcExpectedJson());
        }
    }
       
    protected function getPersonalAccessDetails() {
        return Client::where('personal_access_client', 1)->first();
    }
    
    protected function getClientCredentialsDetails() {
        return Client::where('personal_access_client', 0)->first();
    }
    
    protected function getClientCredentialsToken($scopes) {
        $personalAccessClient = $this->getPersonalAccessDetails();
        
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
        $passwordGrantClient = $this->getClientCredentialsDetails();
    
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
