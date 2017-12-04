<?php

namespace ApiV1Bundle\Tests\Controller;

class VentanillaControllerTest extends ControllerTestCase
{
    public function testListAction() {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('GET', '/api/v1.0/ventanillas');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostAction() {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'puntoAtencion' => 1,
            'identificador' => 'Test 1',
            'colas' => [1]
        ];

        $client->request('POST', '/api/v1.0/ventanillas', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Ventanilla sin colas
        $params['colas'] = [];
        $client->request('POST', '/api/v1.0/ventanillas', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        return $data['additional']['id'];
    }

    /**
     * @depends testPostAction
     */
    public function testPutAction($id) {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'puntoAtencion' => 1,
            'identificador' => 'Test #1',
            'colas' => [1]
        ];

        $client->request('PUT', '/api/v1.0/ventanillas/' . $id, $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Ventanilla inexistente
        $client->request('PUT', '/api/v1.0/ventanillas/0', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostAction
     */
    public function testDeleteAction($id) {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/ventanillas/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
