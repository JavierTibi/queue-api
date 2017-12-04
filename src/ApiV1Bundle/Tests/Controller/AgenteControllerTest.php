<?php

namespace ApiV1Bundle\Tests\Controller;


class AgenteControllerTest extends ControllerTestCase
{
    public function testListAction()
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/agentes');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCrearAgente() {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'nombre' => 'Agente',
            'apellido' => 'McAgenteFace',
            'username' => 'agente@example.com',
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
     * @depends testCrearAgente
     */
    public function testAsignarVentanilla($id) {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('POST', "/api/v1.0/agentes/$id/ventanilla/2");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testCrearAgente
     */
    public function testGetAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/agentes/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testCrearAgente
     */
    public function testGetVentanillasAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', "/api/v1.0/agentes/$id/ventanillas");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testCrearAgente
     */
    public function testEliminarAgente($id) {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/usuarios/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
