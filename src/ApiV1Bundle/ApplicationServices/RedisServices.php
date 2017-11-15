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
            'puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId . ':prioridad:' . $prioridad,
            $fecha->getTimestamp(),
            $value
        );

        if ($val != 1) {
            $errors['errors'] = 'No se ha podido crear la cola';
        }

        return new ValidateResultado(null, $errors);
    }
}