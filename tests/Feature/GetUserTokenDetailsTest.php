<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetUserTokenDetailsTest extends TestCase
{
    private $endpoint = '/v1/user-token-details';
    
   public function testGetUserTokenDetailsUnauthenticated()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders('Bad Token'));
        
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }

    public function testGetUserTokenDetailsWithUserCredentials()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user')));
    
        $response->assertJson([
            'name' => 'Mary',
            'email' => 'mary@example.com',
        ]);
    
        $response->assertStatus(200);
    }
    
    public function testGetUserTokenDetailsWithWrongUserCredentialsTokenScopes()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getUserCredentialsToken('chris@example.com', 'secret', 'verify-password')));
    
        $response->assertJson($this->getWrongScopesJson());
        $response->assertStatus(403);
    }

    public function testGetUserTokenDetailsWithClientCredentialsToken()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getAuthorizationHeaders($this->getClientCredentialsToken('user')));
    
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }

}