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
    public function search($cuil, $codigo)
    {
        $query = $this->getRepository()->createQueryBuilder('t');
        $query->join('t.datosTurno', 'd');
        $query->where('d.cuil = :cuil')->setParameter('cuil', $cuil);
        $query->andWhere('lower(t.codigo) LIKE :codigo')->setParameter('codigo', strtolower($codigo) . '%');
        $query->orderBy('t.id', 'DESC');
        return $query->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }
}
