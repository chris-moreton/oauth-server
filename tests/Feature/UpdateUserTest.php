<?php

namespace Tests\Feature;

use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    private $endpoint = '/v1/users/{id}';
    
    public function testUpdateUserSimple()
    {
        $endpoint = str_replace('{id}', 1, $this->endpoint);
    
        $params = $this->getUpdateUserParams();
        $params['email'] = 'chris@example.com';
    
        $response = $this->json('PUT', $endpoint, $params, $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('user')));
    
        $response->assertJson(['email' => 'chris@example.com']);
        $response->assertStatus(200);
    }
    
    public function testUpdateUserUnauthenticated()
    {
        $endpoint = str_replace('{id}', 1, $this->endpoint);
        $response = $this->json('PUT', $endpoint, $this->getUpdateUserParams(), $this->getAuthorizationHeaders('Bad Token'));
        
        $response->assertJson([
           'error' => 'Unauthenticated.'
        ]);
        
        $response->assertStatus(401);
    }
    
    public function testUpdateIncorrectUser()
    {
        $endpoint = str_replace('{id}', 2, $this->endpoint);
        $response = $this->json('PUT', $endpoint, $this->getUpdateUserParams(), $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('user')));
    
        $response->assertJson($this->getWrongUserJson());
    
        $response->assertStatus(403);
    }
    
    public function testUpdateNonExistentUser()
    {
        $endpoint = str_replace('{id}', 999999, $this->endpoint);
        $response = $this->json('PUT', $endpoint, $this->getUpdateUserParams(), $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('user')));
    
        $response->assertJson($this->getUserNotFoundJson());
    
        $response->assertStatus(404);
    }
    
    public function testUpdateUserWithInvalidField()
    {
        $endpoint = str_replace('{id}', 1, $this->endpoint);
        
        $params = $this->getUpdateUserParams();
        $params['newfield'] = 'hi!';
        
        $response = $this->json('PUT', $endpoint, $params, $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('user')));
    
        $response->assertJson(['error' => 'Invalid field: newfield']);
    
        $response->assertStatus(400);
    }
    
    public function testUpdateUserPassword()
    {
        /***********************************
         * Create User
         ***********************************/
        $name = 'testCreateUserPassword_' . time();
        $email = $name . '@netsensia.com';
        
        $password1 = 'pass' . $name;
        $password2 = 'pass2' . $name;
        
        $params = [
            'name' => $name,
            'email' => $email,
            'password' => $password1
        ];
        
        $response = $this->json('POST', '/v1/users', $params, $this->getAuthorizationHeaders($this->getClientCredentialsToken('admin')));
        
        $newUserId = $response->json()['id'];
        
        $response->assertJson(['name' => $name, 'email' => $email]);
        $response->assertStatus(201);
        
        /***********************************
         * Check password
         ***********************************/
        
        $response = $this->json('POST', '/v1/users/' . $email . '/passwordcheck', ['password' => $password1], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
        $response->assertJson(['verified' => true]);
        
        /***********************************
         * Update Password
         ***********************************/
        
        $params['password'] = $password2;
        $endpoint = str_replace('{id}', $newUserId, $this->endpoint);
        $token = $this->getUserCredentialsToken($email, $password1, 'user');
        $headers = $this->getAuthorizationHeaders($token);
        $response = $this->putJson($endpoint, $params, $headers);
        
        $response->assertJson(['name' => $name, 'email' => $email]);
        $response->assertStatus(200);

        /***********************************
         * Check success for new password
         ***********************************/
        
        $response = $this->json('POST', '/v1/users/' . $email . '/passwordcheck', ['password' => $password2], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
        $response->assertJson(['verified' => true]);
        
        /***********************************
         * Check failure for old password
         ***********************************/
        
        $response = $this->json('POST', '/v1/users/' . $email . '/passwordcheck', ['password' => $password1], $this->getAuthorizationHeaders($this->getClientCredentialsToken('*')));
        $response->assertJson(['verified' => false]);
    }
    
    public function testUpdateUserWrongScopes()
    {
        $endpoint = str_replace('{id}', 1, $this->endpoint);
        $response = $this->json('PUT', $endpoint, $this->getUpdateUserParams(), $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('verify-password')));
    
        $response->assertJson($this->getWrongScopesJson());
        $response->assertStatus(403);
    }

    public function testUpdateToExistingEmail()
    {
        $endpoint = str_replace('{id}', 1, $this->endpoint);
        $params  = ['email' => 'mary@example.com'];
        $response = $this->json('PUT', $endpoint, $params, $this->getAuthorizationHeaders($this->getDefaultUserCredentialsToken('user')));
    
        $response->assertJson(['error' => 'Email already exists.']);
        $response->assertStatus(400);
    }
    
    protected function getUpdateUserParams($uniqifier = '') {
        
        $name = $uniqifier . '_' . debug_backtrace()[1]['function'] . '_' . time();
        $email = $name . '@netsensia.com';

        $this->lastParams = [
            'name' => $name,
            'email' => $email,
        ];
        
        return $this->lastParams;
    }
    
    protected function getUserUpdatedJson() {
        return [
            'name'  => $this->lastParams['name'],
            'email' => $this->lastParams['email'],
        ];
    }
}