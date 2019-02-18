<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TaxonomyRepository
 * @package App\Repository
 */
class TaxonomyRepository extends EntityRepository
{
    /**
     * @param array $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder(array $params = [])
    {
        $qb = $this->createQueryBuilder('t');

        $qb->select('t');
        if (!empty($params['orderBy'])) {
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : null;
            $qb->orderBy($params['orderBy'], $orderDir);
        }

        return $qb;
    }

    /**
     * @return array
     */
    public function getAsArray()
    {
        return $this->createQueryBuilder('t')
                        ->select('t.id, t.name')
                        ->getQuery()
                        ->getArrayResult();
    }
}
