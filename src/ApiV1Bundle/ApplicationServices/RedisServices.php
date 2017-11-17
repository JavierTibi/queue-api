<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 15/11/2017
 * Time: 10:28 AM
 */

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Validator\ValidateResultado;

class RedisServices extends SNCServices
{
    /**
     * @param $puntoAtencionId
     * @param $colaId
     * @param $prioridad
     * @param $value
     * @return ValidateResultado
     */
    public function zaddCola($puntoAtencionId, $colaId, $prioridad, $value) {
        $errors = [];
        $fecha = new \DateTime();
        $val = $this->getContainerRedis()->zadd(
            'puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId,
            $prioridad . $fecha->getTimestamp(),
            $value
        );

        if ($val == 0) {
            $errors['errors'] = 'No se ha podido crear la cola';
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * Unifica las colas de una ventanilla
     *
     * @param $puntoAtencionId
     * @param $colas
     * @param $ventanilla
     * @return ValidateResultado
     */
    public function unionColas($puntoAtencionId, $colas, $ventanilla)
    {

        $errors = [];
        $keys = [];

        foreach ($colas as $cola) {
            $keys[] = 'puntoAtencion:' . $puntoAtencionId . ':cola:' . $cola->getId();
        }

        $val = $this->getContainerRedis()->zunionstore('puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId(), $keys);

        if ($val == 0) {
            $errors['errors'] = 'No se ha podido crear la cola';
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * Traer los sets de una cola
     * @param $key
     * @return mixed
     */
    private function zrangeCola($key)
    {
        return $this->getContainerRedis()->zrange($key, 0, -1);
    }

    /**
     * Obtiene los elementos de una cola
     *
     * @param $puntoAtencionId
     * @param $colaId
     * @return mixed
     */
    public function getCola($puntoAtencionId, $colaId)
    {
        return $this->zrangeCola('puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId);
    }

    /**
     * Obtiene los elementos de todos las colas de una ventanilla
     *
     * @param $puntoAtencionId
     * @param $ventanilla
     * @return mixed
     */
    public function getColaVentanilla($puntoAtencionId, $ventanilla)
    {
        return $this->zrangeCola('puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId());
    }

}