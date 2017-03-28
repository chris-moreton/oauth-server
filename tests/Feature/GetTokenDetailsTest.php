<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetTokenDetailsTest extends TestCase
{
    private $endpoint = '/v1/token-details';
    
    public function testGetTokenDetailsUnauthenticated()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getHeaders('Bad Token'));
        
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }

    public function testGetTokenDetailsWithUserCredentials()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user-read')));
    
        $response->assertJson([
            'name' => 'Mary',
            'email' => 'mary@example.com',
        ]);
    
        $response->assertStatus(200);
    }
    
    public function testGetTokenDetailsWithClientCredentials()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getHeaders($this->getClientCredentialsToken('user-read')));
    
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }
    
    public function testGetTokenDetailsWithWrongUserCredentialsTokenScopes()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getHeaders($this->getUserCredentialsToken('chris@example.com', 'secret', 'user-update')));
    
        $response->assertJson($this->getInvalidScopesJson());
        $response->assertStatus(403);
    }

    public function testGetTokenDetailsWithWrongClientCredentialsTokenScopes()
    {
        $response = $this->json('GET', $this->endpoint, [], $this->getHeaders($this->getClientCredentialsToken('user-update')));
    
        $response->assertJson($this->getUnauthenticatedJson());
        $response->assertStatus(401);
    }

}