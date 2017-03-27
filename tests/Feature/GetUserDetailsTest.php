<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    // user-create doesn't give a user ability to create a new user, it just allows
    // creation on behalf of a given user
    private $badScopes = ['user-create', 'admin-read admin-update'];
    
    private $goodScopes = ['*', 'admin-create', 'admin-read admin-create'];
    
    public function testGetUserDetailsUnauthenticated()
    {
        $response = $this->json('GET', '/v1/users/chris@example.com', [], $this->getHeaders('Bad Token'));
        
        $response->assertJson([
            'error' => 'Unauthenticated.'
        ]);
        
        $response->assertStatus(401);
        
    }
}