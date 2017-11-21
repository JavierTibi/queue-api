<?php

namespace ApiV1Bundle\Entity\Getter;


use ApiV1Bundle\ApplicationServices\RedisServices;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\VentanillaRepository;

class TurnoGetter
{

    private $ventanillaRepository;
    private $redisServices;

    /**
     * TurnoGetter constructor.
     * @param VentanillaRepository $ventanillaRepository
     * @param RedisServices $redisServices
     */
    public function __construct(
        VentanillaRepository $ventanillaRepository,
        RedisServices $redisServices
    )
    {
        $this->ventanillaRepository = $ventanillaRepository;
        $this->redisServices = $redisServices;
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
        if($colas->count() == 1) {

            $turnos = $this->redisServices->getCola($params['puntoatencion'], $colas->first()->getId(), $params['offset'], $params['limit']);
            $cantTurnos = count($this->redisServices->getTotalCola($params['puntoatencion'], $colas->first()->getId()));

        } elseif ($colas->count() > 1) {

            $validateCola = $this->redisServices->unionColas($params['puntoatencion'], $colas, $ventanilla);

            if($validateCola->hasError()) {
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
}