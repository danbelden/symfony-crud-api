<?php

namespace AppBundle\Repository;

use AppBundle\Criteria\Model as ModelCriteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Model extends EntityRepository
{
    /**
     * Method to count the given results in the filtered result set
     *
     * @param ModelCriteria $criteria
     * @return int
     */
    public function countByCriteria(ModelCriteria $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('m');
        $queryBuilder->select('count(m.uuid)');

        $this->appendFiltersFromCriteria($criteria, $queryBuilder);

        $query = $queryBuilder->getQuery();

        return (int)$query->getSingleScalarResult();
    }

    /**
     * Method to find models by the given criteria object
     *
     * @param ModelCriteria $criteria
     * @return Model[]
     */
    public function findByCriteria(ModelCriteria $criteria)
    {
        $queryBuilder = $this->createQueryBuilder('m');

        $this->appendFiltersFromCriteria($criteria, $queryBuilder)
            ->appendLimitOffsetOrderFromCriteria($criteria, $queryBuilder);

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    /**
     * Helper method to append the model filter parameters from a given
     * criteria, to a given query builder instance
     *
     * @param ModelCriteria $criteria
     * @param QueryBuilder $queryBuilder
     * @return $this
     */
    private function appendFiltersFromCriteria(
        ModelCriteria $criteria,
        QueryBuilder &$queryBuilder
    ) {
        if (!empty($criteria->getName())) {
            $queryBuilder->where('m.name = :name')
                ->setParameter('name', $criteria->getName());
        }

        return $this;
    }

    /**
     * Helper method to append the limit/offset/sort parameters from a given
     * criteria, to a given query builder instance
     *
     * @param ModelCriteria $criteria
     * @param QueryBuilder $queryBuilder
     * @return $this
     */
    private function appendLimitOffsetOrderFromCriteria(
        ModelCriteria $criteria,
        QueryBuilder &$queryBuilder
    ) {
        $limit = $criteria->getLimitOrDefault();
        $queryBuilder->setMaxResults($limit);

        $offset = $criteria->getOffsetOrDefault();
        $queryBuilder->setFirstResult($offset);

        if (!empty($criteria->getOrderField())) {
            $orderDirection = $criteria->getOrderDirectionOrDefault();
            $queryBuilder->orderBy($criteria->getOrderField(), $orderDirection);
        }

        return $this;
    }
}
