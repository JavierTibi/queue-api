<?php

namespace ApiV1Bundle\Tests\Controller;


class ColaControllerTest extends ControllerTestCase
{

    public function testPostAction()
    {
        $client = static::createClient();
        $client->followRedirects();
        $params = [
            'nombre' => 'Cola Test',
            'grupoTramite' => 2,
            'puntoAtencion' => 1
        ];

        $client->request('POST', '/api/v1.0/colas/grupotramite', $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['additional']['id'];
    }

    /**
     * @depends testPostAction
     */
    public function testGet($id)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/colas/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testList()
    {
        $puntoAtencion = 1;

        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/colas?puntoatencion='.$puntoAtencion.'&offset=0&limit=10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutAction()
    {
        $grupoTramite = 2;

        $client = static::createClient();
        $client->followRedirects();
        $params = [
            'nombre' => 'Cola Test Modificada'
        ];

        $client->request('PUT', '/api/v1.0/colas/grupotramite/' . $grupoTramite, $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDelete() {
        $grupoTramite = 2;

        $client = static::createClient();
        $client->followRedirects();
        $client->request('DELETE', '/api/v1.0/colas/grupotramite/' . $grupoTramite);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}