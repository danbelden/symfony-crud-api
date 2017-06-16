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
        if (is_numeric($criteria->getLimit())) {
            $queryBuilder->setMaxResults($criteria->getLimit());
        }

        if (is_numeric($criteria->getOffset())) {
            $queryBuilder->setFirstResult($criteria->getOffset());
        }

        if (!empty($criteria->getOrderField())) {
            $queryBuilder->orderBy($criteria->getOrderField());
            if (!empty($criteria->getOrderDirection())) {
                $queryBuilder->orderBy(
                    $criteria->getOrderField(),
                    $criteria->getOrderDirection()
                );
            }
        }

        return $this;
    }
}
