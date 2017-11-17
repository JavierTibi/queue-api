<?php

namespace ApiV1Bundle\ExternalServices;


class SNTTurnosService
{
    private $integrationService;

    /**
     * SNTTurnosService constructor.
     * @param $integrationService
     */
    public function __construct(SNCExternalService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * @param $params
     * @return array
     */
    public function getListTurnos($params)
    {
        $result = [];
        $parameters = [
            'puntoatencion' => (int)$params['puntoatencion'],
            'fecha' => $params['fecha'],
            'offset' => $params['offset'],
            'limit' => $params['limit']
        ];

        $url = $this->integrationService->getUrl('turnos.fecha');
        $response =  $this->integrationService->get($url, $parameters);

        //transforma el resultado en array para enviarlo a Respuesta Data.
        foreach ($response->result as $item) {
            $item->campos =  (array) $item->campos;
            $result[] = (array) $item;
        }

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function getItemTurnoSNT($id)
    {
        $url = $this->integrationService->getUrl('turnos', $id);
        $response =  $this->integrationService->get($url);

        //transforma atributos stdclass a array
        $response->result->datos_turno->campos = (array) $response->result->datos_turno->campos;
        $response->result->datos_turno = (array) $response->result->datos_turno;
        $response->result->tramite = (array) $response->result->tramite;
        $response->result->grupo_tramite = (array) $response->result->grupo_tramite;
        $response->result->punto_atencion = (array) $response->result->punto_atencion;

        return (array) $response->result;
    }

}