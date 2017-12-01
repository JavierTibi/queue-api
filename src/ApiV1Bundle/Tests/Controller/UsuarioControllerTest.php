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

    public function testPostActionAdmin()
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Admin',
            'apellido' => 'McAdminFace',
            'username' => 'admin@example.com',
            'rol' => 1
        ];

        $client->request('POST', '/api/v1.0/usuarios', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Parametro faltante
        unset($params['apellido']);
        $client->request('POST', '/api/v1.0/usuarios', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());


        return $data['additional']['id'];
    }

    public function testPostActionResp()
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Resp',
            'apellido' => 'McRespFace',
            'username' => 'resp@example.com',
            'rol' => 2,
            'puntoAtencion' => 1
        ];

        $client->request('POST', '/api/v1.0/usuarios', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Punto de atención inexistente
        $params['puntoAtencion'] = 0;
        $client->request('POST', '/api/v1.0/usuarios', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $params['puntoAtencion'] = 1;

        // Apellido faltante
        unset($params['apellido']);
        $client->request('POST', '/api/v1.0/usuarios', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        // Username faltante
        $params['apellido'] = 'McRespFace';
        unset($params['username']);
        $client->request('POST', '/api/v1.0/usuarios', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());


        return $data['additional']['id'];
    }

    /**
     * @depends testPostActionAdmin
     */
    public function testPutAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Dude',
            'apellido' => 'McDudeFace',
            'username' => 'dud@example.com',
            'rol' => 1,
            'puntoAtencion' => 1,
            'ventanillas' => [2, 4]
        ];

        $client->request('PUT', '/api/v1.0/usuarios/' . $id, $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostActionAdmin
     */
    public function testGetAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('GET', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostActionAdmin
     */
    public function testDeleteActionAdmin($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostActionResp
     */
    public function testDeleteActionResp($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
