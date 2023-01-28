<?php
 
namespace Tests;
abstract class ApiTestCase extends TestCase
{
    public function loginAsAdmin(): string
    {
        $email = env('TEST_ADMIN_EMAIL');
        $password = env('TEST_ADMIN_CLEAR_PASSWORD');
 
        $response = $this->json('POST', '/api/auth/login', [
            'email' => $email,
            'password' => $password
        ]);
        $parsedResponse = json_decode($response->content());
        $token = $parsedResponse->token;
        return $token;
    }
    public function loginAsUser(): string
    {
        $email = env('TEST_USER_EMAIL');
        $password = env('TEST_USER_CLEAR_PASSWORD');
 
        $response = $this->json('POST', '/api/auth/login', [
            'email' => $email,
            'password' => $password
        ]);
        $parsedResponse = json_decode($response->content());
        $token = $parsedResponse->token;
        return $token;
    }
}  