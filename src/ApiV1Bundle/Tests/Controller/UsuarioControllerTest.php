<?php
namespace ApiV1Bundle\Tests\Controller;


class UsuarioControllerTest extends ControllerTestCase
{
    public function testGetListAction()
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/usuarios');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostAction()
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Dude',
            'apellido' => 'McDudeFace',
            'username' => 'duda@example.com',
            'rol' => 3,
            'puntoAtencion' => 1,
            'ventanillas' => [2, 4]
        ];

        $client->request('POST', '/api/v1.0/usuarios', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        return $data['additional']['id'];
    }

    /**
     * @depends testPostAction
     */
    public function testPutAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Dude',
            'apellido' => 'McDudeFace',
            'username' => 'dud@example.com',
            'rol' => 3,
            'puntoAtencion' => 1,
            'ventanillas' => [2, 4]
        ];

        $client->request('PUT', '/api/v1.0/usuarios/' . $id, $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostAction
     */
    public function testGetAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('GET', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostAction
     */
    public function testDeleteAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
