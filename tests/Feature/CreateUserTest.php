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
        $response = $this->json('POST', '/v1/users', $this->getCreateUserParams(), $this->getAuthorizationHeaders('Bad Token'));
        
        $response->assertJson([
           'error' => 'Unauthenticated.'
        ]);
        
        $response->assertStatus(401);
    }
    
    public function testCreateUserWithClientCredentialsTokenWithBadScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->badScopes, 'getCreateUserParams', 'getClientCredentialsToken', 'getWrongScopesJson', 403);
    }

    public function testCreateUserWithClientCredentialsTokenWithGoodScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->goodScopes, 'getCreateUserParams', 'getClientCredentialsToken', 'getUserCreatedJson', 201);
    }

    public function testCreateUserWithUserTokenWithBadScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->badScopes, 'getCreateUserParams', 'getDefaultUserCredentialsToken', 'getWrongScopesJson', 403);
    }

    public function testCreateUserWithUserTokenWithGoodScopes()
    {
        $this->routeTest('POST', '/v1/users', $this->goodScopes, 'getCreateUserParams', 'getDefaultUserCredentialsToken', 'getUserCreatedJson', 201);
    }
    
    public function testCreateUserPasswordCanLogin()
    {
        $name = 'testCreateUserPassword_' . time();
        $email = $name . '@netsensia.com';
        $password = 'pass_' . $name;
        
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];
        
        $response = $this->json('POST', '/v1/users', $params, $this->getAuthorizationHeaders($this->getClientCredentialsToken('admin-create')));
        
        $response->assertJson(['name' => $name, 'email' => $email]);
        $response->assertStatus(201);
        
        $response = $this->json('POST', '/v1/users/' . $email . '/passwordcheck', ['password' => $password], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
        $response->assertJson(['verified' => true]);
    }
    
    public function testConflictWhenAttemptingToCreateUsersWithSameEmailAddress()
    {
        $params = $this->getCreateUserParams();
        
        $response = $this->json('POST', '/v1/users',
            $params,
            $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('admin-create'))
        );
        
        $response->assertJson($this->getUserCreatedJson());
        $response->assertStatus(201);
        
        $response = $this->json('POST', '/v1/users',
            $params,
            $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('admin-create'))
        );

        $response->assertJson(['error' => 'email already exists: ' . $params['email']]);
        $response->assertStatus(409);
        
    }
    
    protected function getCreateUserParams($uniqifier = '') {
        
        $name = $uniqifier . '_' . debug_backtrace()[2]['function'] . '_' . time();
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