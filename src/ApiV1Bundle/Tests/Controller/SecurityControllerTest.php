<?php
namespace ApiV1Bundle\Tests\Controller;

class SecurityControllerTest extends ControllerTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $client->followRedirects();
        $params = ['username' => 'test', 'password' => 'test'];
        $client->request('POST', '/api/v1.0/auth/login', $params);
        // content
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(array_key_exists('token', $content));
        return $content['token'];
    }

    /**
     * Test token
     * @depends testLogin
     */
    public function testToken($token)
    {
        $client = static::createClient();
        $client->followRedirects();
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json'
        ];
        $client->request('POST', '/api/v1.0/auth/test', [], [], $headers);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $token;
    }

    /**
     * Test logout user
     * @depends testToken
     */
    public function testLogout($token)
    {
        $client = static::createClient();
        $client->followRedirects();
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json'
        ];
        $client->request('POST', '/api/v1.0/auth/logout', [], [], $headers);
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(array_key_exists('status', $content));
        $this->assertEquals('SUCCESS', $content['status']);
        return $token;
    }

    /**
     * Test fobidden
     * @depends testLogout
     */
    public function testFobidden($token)
    {
        $client = static::createClient();
        $client->followRedirects();
        $headers = [
            'HTTP_AUTHORIZATION' => "Bearer {$token}",
            'CONTENT_TYPE' => 'application/json'
                ];
        $client->request('POST', '/api/v1.0/auth/test', [], [], $headers);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

}
