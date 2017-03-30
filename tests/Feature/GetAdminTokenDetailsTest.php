<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetAdminTokenDetailsTest extends TestCase
{
    private $endpoint = '/v1/token-scopes';
    
   public function testGetAdminTokenDetailsUnauthenticated()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders('Bad Token'));
        
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }

    public function testGetAdminTokenDetailsWithUserCredentialsAndBadScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user-read')));
    
        $response->assertStatus(403);
    }

    public function testGetAdminTokenDetailsWithUserCredentialsAndGoodScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user-read admin-read')));
    
        $response->assertStatus(200);
    }
    
    public function testGetAdminTokenDetailsWithClientCredentialsAndBadScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getClientCredentialsToken('user-update')));
    
        $response->assertStatus(403);
    }
    
    public function testGetAdminTokenDetailsWithClientCredentialsAndGoodScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getClientCredentialsToken('admin-read admin-update')));
    
        $response->assertJson(['scopes' => ['admin-read', 'admin-update']]);
        $response->assertStatus(200);
    }

}