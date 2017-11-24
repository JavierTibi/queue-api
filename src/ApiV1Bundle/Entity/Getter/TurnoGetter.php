<?php

namespace ApiV1Bundle\Entity\Getter;


use ApiV1Bundle\ApplicationServices\RedisServices;
use ApiV1Bundle\Entity\Turno;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\TurnoRepository;
use ApiV1Bundle\Repository\VentanillaRepository;

class TurnoGetter
{

    private $ventanillaRepository;
    private $redisServices;
    private $turnoRepository;

    /**
     * TurnoGetter constructor.
     * @param VentanillaRepository $ventanillaRepository
     * @param RedisServices $redisServices
     * @param TurnoRepository $turnoRepository
     */
    public function __construct(
        VentanillaRepository $ventanillaRepository,
        RedisServices $redisServices,
        TurnoRepository $turnoRepository
    )
    {
        $this->ventanillaRepository = $ventanillaRepository;
        $this->redisServices = $redisServices;
        $this->turnoRepository = $turnoRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function getAll($params, $ventanilla)
    {
        $colas = $ventanilla->getColas();
        return $this->getTurnos($colas, $ventanilla, $params);
    }

    /**
     * @param $colas
     * @param $ventanilla
     * @param $params
     * @return ValidateResultado
     */
    private function getTurnos($colas, $ventanilla, $params)
    {

        if ($colas->count() > 0) {

            $validateCola = $this->redisServices->unionColas($params['puntoatencion'], $ventanilla);

            if ($validateCola->hasError()) {
                return $validateCola;
            }

            $turnos = $this->redisServices->getColaVentanilla($params['puntoatencion'], $ventanilla, $params['offset'], $params['limit']);
            $cantTurnos = count($this->redisServices->getColaVentanilla($params['puntoatencion'], $ventanilla, 0, -1));

        } else {
            $errors['Cola'] = 'La ventanilla no tiene cola';
            return new ValidateResultado(null, $errors);
        }

        $response = [
            'turnos' => $this->parseTurnos($turnos),
            'cantTurnos' => $cantTurnos
        ];

        return new ValidateResultado(json_encode($response), []);
    }

    /**
     * @param $list
     * @return array
     */
    private function parseTurnos($list)
    {
        $result = [];
        foreach ($list as $item) {
            $result[] = (array) json_decode($item);
        }
        return $result;
    }

    /**
     * Obtiene un turno del SNC
     *
     * @param $puntoAtencionId
     * @param $ventanilla
     * @return ValidateResultado
     */
    public function getProximoTurno($puntoAtencionId, $ventanilla)
    {
        $proximoTurno = $this->redisServices->getProximoTurno($puntoAtencionId, $ventanilla);

        if ($proximoTurno) {
            $turno = $this->getTurno($puntoAtencionId, json_decode($proximoTurno));

            if ($turno) {
                return new ValidateResultado($turno, []);
            }

            $errors['Turnos'] = 'Turno no encontrado en el Sistema Nacional de Turnos.';
            return new ValidateResultado(null, $errors);

        }

        $errors['Turnos'] = 'No hay más turnos.';
        return new ValidateResultado(null, $errors);
    }

    private function getTurno($puntoAtencionId, $turno)
    {
        return $this->turnoRepository->search($turno->cuil, $turno->codigo, $puntoAtencionId);
    }


}