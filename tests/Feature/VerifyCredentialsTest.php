<?php

namespace Tests\Feature;

use Tests\TestCase;

class VerifyCredentialsTest extends TestCase
{
    private $endpoint = '/v1/users/{email}/passwordcheck';
    
    public function testVerifyPasswordUnauthenticated()
    {
        $endpoint = str_replace('{email}', 'chris@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secret'], $this->getAuthorizationHeaders('Bad Token'));
        
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }
    
    public function testVerifyPasswordForNonExistentUserDetails()
    {
        $endpoint = str_replace('{email}', 'chris2@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secret'], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
    
        $response->assertJson(['verified' => false]);
        $response->assertStatus(200);
    }

    public function testVerifyPasswordForWrongPassword()
    {
        $endpoint = str_replace('{email}', 'chris@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secrets'], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
    
        $response->assertJson(['verified' => false]);
        $response->assertStatus(200);
    }

    public function testVerifyPasswordWithGoodCredentials()
    {
        $endpoint = str_replace('{email}', 'chris@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secret'], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
    
        $response->assertJson(['verified' => true]);
        $response->assertStatus(200);
    }

    public function testVerifyPasswordWithGoodCredentialsGoodScope()
    {
        $endpoint = str_replace('{email}', 'chris@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secret'], $this->getAuthorizationHeaders($this->getClientCredentialsToken('admin')));
    
        $response->assertJson(['verified' => true]);
        $response->assertStatus(200);
    }
    
    public function testVerifyPasswordWithGoodCredentialsBadScopes()
    {
        $endpoint = str_replace('{email}', 'chris@example.com', $this->endpoint);
        $response = $this->json('POST', $endpoint, ['password' => 'secret'], $this->getAuthorizationHeaders($this->getClientCredentialsToken('user')));
    
        $response->assertJson($this->getWrongScopesJson());
        $response->assertStatus(403);
    }
    
}