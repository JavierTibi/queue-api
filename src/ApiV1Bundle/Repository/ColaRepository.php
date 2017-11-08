<?php

namespace ApiV1Bundle\Repository;

/**
 * ColaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ColaRepository extends ApiRepository
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */

    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('ApiV1Bundle:Cola');
    }

    /**
     * Obtiene todas las colas para un punto de atención
     * @param $puntoAtencionId
     * @param $offset
     * @param $limit
     * @return array
     */
    public function findAllPaginate($puntoAtencionId, $offset, $limit)
    {
        $query = $this->getRepository()->createQueryBuilder('c');

        $query->select([
            'c.nombre',
            'c.numero',
            'c.tipo'
        ])
            ->where('a.puntoAtencion = :puntoAtencionId')
            ->setParameter('puntoAtencionId', $puntoAtencionId);

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);
        $query->orderBy('a.id', 'ASC');
        return $query->getQuery()->getResult();
    }
}
