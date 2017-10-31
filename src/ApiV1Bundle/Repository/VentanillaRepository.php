<?php

namespace ApiV1Bundle\Repository;

/**
 * VentanillaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VentanillaRepository extends ApiRepository
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */

    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('ApiV1Bundle:Ventanilla');
    }

    public function findAllPaginate($puntoAtencionId, $offset, $limit)
    {

        $query = $this->getRepository()->createQueryBuilder('v');

        $query->select([
            'v.identificador'
        ])
            ->where('v.puntoAtencion = :puntoAtencionId')
            ->setParameter('puntoAtencionId', $puntoAtencionId);

        $query->setFirstResult($offset);
        $query->setMaxResults($limit);
        $query->orderBy('v.id', 'ASC');
        return $query->getQuery()->getResult();
    }
}
