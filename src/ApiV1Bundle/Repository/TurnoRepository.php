<?php

namespace ApiV1Bundle\Repository;
use ApiV1Bundle\ApplicationServices\TurnoServices;
use ApiV1Bundle\Entity\Turno;

/**
 * TurnoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TurnoRepository extends ApiRepository
{

    /**
     * @return \Doctrine\ORM\EntityRepository
     */

    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('ApiV1Bundle:Turno');
    }

    /**
     * Busqueda de turnos por cuil y código
     *
     * @param $cuil
     * @param $codigo
     * @return mixed
     */
    public function search($cuil, $codigo, $puntoAtencionId = null)
    {
        $query = $this->getRepository()->createQueryBuilder('t');
        $query->join('t.datosTurno', 'd');
        $query->where('d.cuil = :cuil')->setParameter('cuil', $cuil);
        $query->andWhere('lower(t.codigo) LIKE :codigo')->setParameter('codigo', strtolower($codigo) . '%');

        if (isset($puntoAtencionId)) {
            $query->andWhere('t.puntoAtencion = :pa')->setParameter('pa', $puntoAtencionId);
        }

        $query->orderBy('t.id', 'DESC');
        return $query->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    /**
    * Busqueda de turnos ya recepcionados
    * @param $codigoTurnos string[]
    */
    public function turnosRecepcionados($fecha, $puntoAtencionId)
    {
        $fecha = new \DateTime($fecha);
        $fechaFormat = $fecha->format('Y-m-d');

        $query = $this->getRepository()->createQueryBuilder('t');
         $query->select([
            't.codigo'
        ]);
        $query->where('t.puntoAtencion = :puntoAtencionId')->setParameter('puntoAtencionId', $puntoAtencionId);
        $query->andWhere('t.estado = :estado')->setParameter('estado', Turno::ESTADO_RECEPCIONADO); 
        $query->andWhere('t.fecha = :fecha')->setParameter('fecha', $fechaFormat);
        $query->orderBy('t.id', 'DESC');
        return $query->getQuery()->getResult();
    }
}
