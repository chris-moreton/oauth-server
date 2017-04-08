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
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user')));
    
        $response->assertStatus(403);
    }

    public function testGetAdminTokenDetailsWithUserCredentialsAndGoodScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user admin')));
    
        $response->assertStatus(200);
    }
    
    public function testGetAdminTokenDetailsWithClientCredentialsAndBadScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getClientCredentialsToken('user')));
    
        $response->assertStatus(403);
    }
    
    public function testGetAdminTokenDetailsWithClientCredentialsAndGoodScopeToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getClientCredentialsToken('admin')));
    
        $response->assertJson(['scopes' => ['admin']]);
        $response->assertStatus(200);
    }

}