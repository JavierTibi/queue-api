<?php

namespace ApiV1Bundle\Repository;


class UsuarioRepository extends ApiRepository
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */

    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('ApiV1Bundle:Usuario');
    }

    public function findAllPaginate($offset, $limit)
    {
        $query = $this->getRepository()->createQueryBuilder('u');
        $query->setFirstResult($offset);
        $query->setMaxResults($limit);
        $query->orderBy('u.user', 'ASC');
        return  $query->getQuery()->getResult();

    }
}