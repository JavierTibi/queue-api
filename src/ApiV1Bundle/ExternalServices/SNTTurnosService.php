<?php

namespace ApiV1Bundle\ExternalServices;

use ApiV1Bundle\Entity\Validator\ValidateResultado;

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
     * @return ValidateResultado
     */
    public function getListTurnos($params)
    {
        $parameters = [
            'puntoatencion' => (int)$params['puntoatencion'],
            'fecha' => $params['fecha'],
            'offset' => $params['offset'],
            'limit' => $params['limit']
        ];

        $url = $this->integrationService->getUrl('turnos.fecha');
        $response =  $this->integrationService->get($url, $parameters);

        if (isset($response->metadata)) {
            return new ValidateResultado($response, []);
        }

        $errors['Turnos'] = $response->userMessage->errors->turno[0];

        return new ValidateResultado(null, $errors);
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

    /**
     * Busqueda de turnos en el Sistema Nacional de Turnos
     * @param $codigo
     * @return ValidateResultado
     */
    public function searchTurnoSNT($codigo)
    {
        $url = $this->integrationService->getUrl('turnos.buscar', null, ['codigo' => $codigo]);
        $response =  $this->integrationService->get($url);
        if (isset($response->result)) {
            return new ValidateResultado($response->result, []);
        }
        // errors
        $errors = (array) $response->userMessage->errors;
        return new ValidateResultado(null, $errors);
    }
}
