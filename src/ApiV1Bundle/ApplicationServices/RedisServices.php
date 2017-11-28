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
     * Agrega un elemento a la cola
     *
     * @param $puntoAtencionId
     * @param $colaId
     * @param $prioridad
     * @param $turno
     * @return ValidateResultado
     */
    public function zaddCola($puntoAtencionId, $colaId, $prioridad, $turno)
    {
        $errors = [];
        $fecha = new \DateTime();
        $hora = new \DateTime($turno->getHora());

        $array = [
            'tramite' => $turno->getTramite(),
            'codigo' => $turno->getCodigo(),
            'horario' => $hora->format('H:i'),
            'cuil' => $turno->getDatosTurno()->getCuil(),
            'nombre' => $turno->getDatosTurno()->getNombre(),
            'apellido' => $turno->getDatosTurno()->getApellido(),
            'cola' => $colaId
        ];

        $val = $this->getContainerRedis()->zadd(
            'puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId,
            $prioridad . $fecha->getTimestamp(),
            json_encode($array)
        );

        if ($val == 0) {
            $errors['errors'] = 'El turno ya se encuentra en el Sistema de Colas.';
            return new ValidateResultado(null, $errors);
        }

        return new ValidateResultado($turno, []);
    }

    /**
     * Unifica las colas de una ventanilla
     *
     * @param $puntoAtencionId
     * @param $ventanilla
     * @return ValidateResultado
     */
    public function unionColas($puntoAtencionId, $ventanilla)
    {
        $errors = [];
        $keys = [];

        foreach ($ventanilla->getColas() as $cola) {
            if ($this->exists('puntoAtencion:' . $puntoAtencionId . ':cola:' . $cola->getId()) > 0) {
                $keys[] = 'puntoAtencion:' . $puntoAtencionId . ':cola:' . $cola->getId();
            }
        }

        if(count($keys) > 0) {
            $val = $this->getContainerRedis()->zunionstore('puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId(), $keys);

            if ($val == 0) {
                $errors['errors'] = 'No se ha podido crear la cola';
            }
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * Traer los sets de una cola
     * @param $key
     * @return mixed
     */
    private function zrangeCola($key, $offset, $limit)
    {
        return $this->getContainerRedis()->zrange($key, $offset, $limit);
    }

    /**
     * Obtiene los elementos de una cola con offset y limit
     *
     * @param $puntoAtencionId
     * @param $colaId
     * @return mixed
     */
    public function getCola($puntoAtencionId, $colaId, $offset, $limit)
    {
        return $this->zrangeCola('puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId, $offset, $limit);
    }

    /**
     * Obtiene todos los elementos de una cola
     *
     * @param $puntoAtencionId
     * @param $colaId
     * @return mixed
     *
     */
    public function getTotalCola($puntoAtencionId, $colaId)
    {
        return $this->zrangeCola('puntoAtencion:' . $puntoAtencionId . ':cola:' . $colaId, 0, -1);
    }

    /**
     * Obtiene los elementos de todos las colas de una ventanilla
     *
     * @param $puntoAtencionId
     * @param $ventanilla
     * @return mixed
     */
    public function getColaVentanilla($puntoAtencionId, $ventanilla, $offset, $limit)
    {
        if ($this->exists('puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId())) {
            return $this->zrangeCola('puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId(), $offset, $limit);
        }

        return [];
    }

    /**
     * Quita el primer elemento de la cola y lo retorna
     *
     * @param $puntoAtencionId
     * @param $ventanilla
     * @return mixed
     */
    public function getProximoTurno($puntoAtencionId, $ventanilla)
    {
        $validateResult = $this->unionColas($puntoAtencionId, $ventanilla);

        if (! $validateResult->hasError()) {
            $cola = 'puntoAtencion:' . $puntoAtencionId . ':ventanilla:' . $ventanilla->getId();

            if ($this->exists($cola)) {
                $turno = $this->getFirstElement($cola);
                $colaOriginal = 'puntoAtencion:' . $puntoAtencionId . ':cola:' . json_decode($turno)->cola;
                $this->remove($colaOriginal, $turno);
                $this->remove($cola, $turno);
                return $turno;
            }
        }
    }

    /**
     * @param $cola
     * @param $value
     * @return mixed
     */
    private function remove($cola, $value)
    {
        return $this->getContainerRedis()->zrem($cola, $value);
    }


    /**
     * @param $cola
     * @return mixed
     */
    private function getFirstElement($cola)
    {
        $turnos =  $this->zrangeCola($cola, 0, -1);
        return $turnos[0];
    }

    /**
     * Obtiene la posicion de un turno en la ocola
     * @param $turno
     * @param $cola
     * @return int
     */
    public function getPosicion($turno, $cola)
    {
        $turnos = $this->getCola(
            $turno->getPuntoAtencion()->getId(),
            $cola->getId(),
            0,
            -1
        );

        for ($i = 0; $i < count($turnos); $i++) {
            if (json_decode($turnos[$i])->codigo == $turno->getCodigo()) {
               return $i;
            }
        }

        return -1;
    }

    /**
     * @param string $key
     * @return int
     */
    private function exists($key)
    {
        return $this->getContainerRedis()->exists($key);
    }

}
