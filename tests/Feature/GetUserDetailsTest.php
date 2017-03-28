<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    
    public function testGetUserDetailsUnauthenticated()
    {
        $response = $this->json('GET', '/v1/users/chris@example.com', [], $this->getHeaders('Bad Token'));
        
        $response->assertJson($this->getUnauthenticatedJson());
        
        $response->assertStatus(401);
    }
    
    public function testGetNonExistentUserDetails()
    {
        $response = $this->json('GET', '/v1/users/chris2@example.com', [], $this->getHeaders($this->getUserCredentialsToken('chris@example.com', 'secret', 'user-read')));
    
        $response->assertJson($this->getWrongUserJson());
    
        $response->assertStatus(401);
    }
    
    public function testGetUserDetailsWithTokenForWrongUser()
    {
        $response = $this->json('GET', '/v1/users/chris@example.com', [], $this->getHeaders($this->getUserCredentialsToken('mary@example.com', 'secret', 'user-read')));
    
        $response->assertJson($this->getWrongUserJson());
    
        $response->assertStatus(401);
    }
    
    public function testGetUserDetailsWithWrongTokenScopes()
    {
        $response = $this->json('GET', '/v1/users/chris@example.com', [], $this->getHeaders($this->getUserCredentialsToken('chris@example.com', 'secret', 'user-update')));
    
        $response->assertStatus(403);
    }
    
    public function testGetUserDetailsWithCorrectTokenScopes()
    {
        $response = $this->json('GET', '/v1/users/chris@example.com', [], $this->getHeaders($this->getUserCredentialsToken('chris@example.com', 'secret', 'user-read')));
    
        $response->assertJson([
            'email' => 'chris@example.com'
        ]);
    
        $response->assertStatus(200);
    }
}