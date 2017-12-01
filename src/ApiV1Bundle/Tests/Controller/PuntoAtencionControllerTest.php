<?php
/**
 * Created by IntelliJ IDEA.
 * User: simon
 * Date: 29/11/17
 * Time: 04:09 PM
 */

namespace ApiV1Bundle\Tests\Controller;


class PuntoAtencionControllerTest extends ControllerTestCase
{
    public function testGetListAction()
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/puntoatencion');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostAction()
    {
        $id = random_int(1, 2147483647);
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'punto_atencion_id_SNT' => $id,
            'nombre' => 'PA de prueba'
        ];

        $client->request('POST', '/api/v1.0/puntosatencion', $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Ya existe
        $client->request('POST', '/api/v1.0/puntosatencion', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        return $id;
    }

    /**
     * @depends testPostAction
     */
    public function testPutAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $params = [
            'punto_atencion_id_SNT' => $id,
            'nombre' => 'PA de prueba #2'
        ];

        $client->request('PUT', '/api/v1.0/puntosatencion/' . $id, $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Punto atención inexistente
        $client->request('PUT', '/api/v1.0/puntosatencion/0', $params);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        // Sin nombre
        $client->request('PUT', '/api/v1.0/puntosatencion/' . $id, []);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostAction
     */
    public function testDeleteAction($id)
    {
        $client = self::createClient();
        $client->followRedirects();

        $client->request('DELETE', '/api/v1.0/puntosatencion/' . $id);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // No existe
        $client->request('DELETE', '/api/v1.0/puntosatencion/0');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}
