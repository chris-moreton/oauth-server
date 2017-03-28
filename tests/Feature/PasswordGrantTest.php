<?php

namespace Tests\Feature;

use Tests\TestCase;

class PasswordGrantTest extends TestCase
{
    private $endpoint = '/oauth/token';
    
    public function testPasswordGrantAllScopes()
    {
        $passwordGrantClient = $this->getClientCredentialsDetails();
      
        $response = $this->post($this->endpoint, [
                'grant_type' => 'password',
                'username' => 'chris@example.com',
                'password' => 'secret',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'scope' => '*',
            ],
            $this->getPostHeaders()
        );
            
        $response->assertStatus(200);
        $response->assertJson($this->getBearerTokenJson());
    }
    
    public function testPasswordGrantWithSelectedScopes()
    {
        $passwordGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'password',
                'username' => 'chris@example.com',
                'password' => 'secret',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'scope' => 'user-read user-update',
            ],
            $this->getPostHeaders()
        );
    
        $response->assertStatus(200);
        $response->assertJson($this->getBearerTokenJson());
    }
    
    public function testPasswordGrantWithInvalidScopes()
    {
        $passwordGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'password',
                'username' => 'chris@example.com',
                'password' => 'secret',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'scope' => 'user-read,user-update',
            ],
            $this->getPostHeaders()
        );
        
        $response->assertJson($this->getInvalidScopesJson());
        $response->assertStatus(400);
    }
    
    
    public function testPasswordGrantBadPassword()
    {
        $passwordGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'password',
                'username' => 'chris@example.com',
                'password' => 'secrets',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'scope' => '*',
            ],
            $this->getPostHeaders()
        );
    
        $response->assertStatus(401);
        $response->assertJson($this->getInvalidCredentialsJson());
    }

    public function testPasswordGrantBadUsername()
    {
        $passwordGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'password',
                'username' => 'chris2@example.com',
                'password' => 'secret',
                'client_id' => $passwordGrantClient->id,
                'client_secret' => $passwordGrantClient->secret,
                'scope' => '*',
            ],
                $this->getPostHeaders()
        );
    
        $response->assertStatus(401);
        $response->assertJson($this->getInvalidCredentialsJson());
    }
}