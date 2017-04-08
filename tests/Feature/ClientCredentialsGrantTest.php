<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientCredentialsGrantTest extends TestCase
{
    private $endpoint = '/oauth/token';
    
    public function testClientCredentialsGrantAllScopes()
    {
        $clientCredentialsGrantClient = $this->getClientCredentialsDetails();
      
        $response = $this->post($this->endpoint, [
                'grant_type' => 'client_credentials',
                'client_id' => $clientCredentialsGrantClient->id,
                'client_secret' => $clientCredentialsGrantClient->secret,
                'scope' => '*',
            ],
            $this->getPostHeaders()
        );
            
        $response->assertStatus(200);
        $response->assertJson($this->getBearerTokenJson());
    }
    
    public function testClientCredentialsGrantWithSelectedScopes()
    {
        $clientCredentialsGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'client_credentials',
                'client_id' => $clientCredentialsGrantClient->id,
                'client_secret' => $clientCredentialsGrantClient->secret,
                'scope' => 'user',
            ],
            $this->getPostHeaders()
        );
    
        $response->assertStatus(200);
        $response->assertJson($this->getBearerTokenJson());
    }
    
    public function testClientCredentialsGrantWithInvalidScopes()
    {
        $clientCredentialsGrantClient = $this->getClientCredentialsDetails();
    
        $response = $this->post($this->endpoint, [
                'grant_type' => 'client_credentials',
                'client_id' => $clientCredentialsGrantClient->id,
                'client_secret' => $clientCredentialsGrantClient->secret,
                'scope' => 'user-read,user-update',
            ],
            $this->getPostHeaders()
        );
        
        $response->assertJson($this->getInvalidScopesJson());
        $response->assertStatus(400);
    }

}