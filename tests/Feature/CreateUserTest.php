<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateUserTest extends TestCase
{
    // user-create doesn't give a user ability to create a new user, it just allows
    // creation on behalf of a given user
    private $badScopes = ['user-create', 'admin-read admin-update'];
    
    private $goodScopes = ['*', 'admin-create', 'admin-read admin-create'];
    
    public function testCreateUserUnauthenticated()
    {
        $this->json('POST', '/v1/users', $this->getCreateUserParams(), $this->getHeaders('Bad Token'))->assertJson([
           'error' => 'Unauthenticated.'
        ]);
    }
    
    public function testCreateUserWithClientCredentialsTokenWithBadScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->badScopes, 'getCreateUserParams', 'getClientCredentialsToken', 'getUnauthenticatedJson');
    }

    public function testCreateUserWithClientCredentialsTokenWithGoodScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->goodScopes, 'getCreateUserParams', 'getClientCredentialsToken', 'getUserCreatedJson');
    }

    public function testCreateUserWithUserTokenWithBadScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->badScopes, 'getCreateUserParams', 'getDefaultUserCredentialsToken', 'getUnauthenticatedJson');
    }

    public function testCreateUserWithUserTokenWithGoodScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->goodScopes, 'getCreateUserParams', 'getDefaultUserCredentialsToken', 'getUserCreatedJson');
    }
    
    protected function getCreateUserParams($uniqifier = '') {
        
        $name = $uniqifier . '_' . debug_backtrace()[1]['function'] . '_' . time();
        $email = $name . '@netsensia.com';
        
        $this->lastParams = [
            'name' => $name,
            'email' => $email,
            'password' => 'asdasd',
        ];
        
        return $this->lastParams;
    }
    
    protected function getUserCreatedJson() {
        return [
            'name'  => $this->lastParams['name'],
            'email' => $this->lastParams['email'],
        ];
    }
}