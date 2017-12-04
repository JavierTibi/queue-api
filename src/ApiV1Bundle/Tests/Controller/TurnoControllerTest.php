<?php

namespace ApiV1Bundle\Tests\Controller;


class TurnoControllerTest extends ControllerTestCase
{
    public function testPostAction()
    {
        $today = date('Y-m-d') . 'T03:00:00.000Z';
        $client = static::createClient();
        $client->followRedirects();
        $params = [
            "puntoAtencion" => 1,
            "tramite" => "Tramite Test",
	        "grupoTramite"=> 1,
            "fecha"=> $today,
            "hora" => "12:00",
            "estado" => 3,
	        "codigo" => "sDGFERvx114",
            "prioridad" => 1,
	        "datosTurno" => [
                "nombre" => "Pepe",
                "apellido" => "Pompin",
                "cuil" => 20111111110,
                "email" => "pepe@aol.com",
                "telefono" => 48243541,
                "campos" => [
                    "sexo" => "M",
                    "edad" => 31
                    ]
            ]
        ];

        $client->request('POST', '/api/v1.0/turnos', $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        return $response['additional']['id'];
    }

    /**
     * @depends testPostAction
     */
    public function testGetPositionAction($id)
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/turnos/'.$id.'/posicion');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetListTurnosRecepcionadoAction()
    {
        $puntoAtencion = 1;
        $ventanilla = 2;
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/turnos?puntoatencion='.$puntoAtencion.'&ventanilla='.$ventanilla.'&offset=0&limit=10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetNextTurnosAction()
    {
        $puntoAtencion = 1;
        $ventanilla = 2;
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/api/v1.0/turnos/proximo?puntoatencion='.$puntoAtencion.'&ventanilla='.$ventanilla);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    /**
     * Cambiar a estado terminado
     **/
    public function testCambiarEstadoAction()
    {
        $params = [
            "cuil" => 20111111110,
            "codigo" => "sDGFERvx114",
            "estado" => 5,
            "prioridad" => 2,
            "motivo" => "Terminado"
        ];

        $client = static::createClient();
        $client->followRedirects();
        $client->request('POST', '/api/v1.0/turnos/estado', $params);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}