<?php

namespace Tests\AppBundle\Criteria;

use AppBundle\Criteria\Model as ModelCriteria;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testNameGetAndSet()
    {
        $criteria = new ModelCriteria();
        $defaultName = $criteria->getName();

        $newName = 'test';
        $this->assertNotSame($newName, $defaultName);

        $criteria->setName($newName);
        $updatedName = $criteria->getName();
        $this->assertSame($newName, $updatedName);
    }

    public function testLimitGetAndSet()
    {
        $criteria = new ModelCriteria();
        $defaultLimit = $criteria->getLimit();

        $newLimit = 10;
        $this->assertNotSame($newLimit, $defaultLimit);

        $criteria->setLimit($newLimit);
        $updatedLimit = $criteria->getLimit();
        $this->assertSame($newLimit, $updatedLimit);
    }

    public function testLimitOrDefaultGetter()
    {
        $criteria = new ModelCriteria();
        $defaultLimit = $criteria->getLimit();
        $this->assertNull($defaultLimit);

        $limitOrDefault = $criteria->getLimitOrDefault();
        $this->assertNotNull($limitOrDefault);
        $this->assertSame($criteria::DEFAULT_LIMIT, $limitOrDefault);
    }

    public function testOffsetGetAndSet()
    {
        $criteria = new ModelCriteria();
        $defaultOffset = $criteria->getOffset();

        $newOffset = 1;
        $this->assertNotSame($newOffset, $defaultOffset);

        $criteria->setOffset($newOffset);
        $updatedOffset = $criteria->getOffset();
        $this->assertSame($newOffset, $updatedOffset);
    }

    public function testOffsetOrDefaultGetter()
    {
        $criteria = new ModelCriteria();
        $defaultOffset = $criteria->getOffset();
        $this->assertNull($defaultOffset);

        $offsetOrDefault = $criteria->getOffsetOrDefault();
        $this->assertNotNull($offsetOrDefault);
        $this->assertSame($criteria::DEFAULT_OFFSET, $offsetOrDefault);
    }

    public function testOrderFieldGetAndSet()
    {
        $criteria = new ModelCriteria();
        $defaultOrderField = $criteria->getOrderField();

        $newOrderField = 'name';
        $this->assertNotSame($newOrderField, $defaultOrderField);

        $criteria->setOrderField($newOrderField);
        $updatedOrderField = $criteria->getOrderField();
        $this->assertSame($newOrderField, $updatedOrderField);
    }

    public function testOrderDirectionGetAndSet()
    {
        $criteria = new ModelCriteria();
        $defaultOrderDir = $criteria->getOrderDirection();

        $newOrderDir = 'DESC';
        $this->assertNotSame($newOrderDir, $defaultOrderDir);

        $criteria->setOrderDirection($newOrderDir);
        $updatedOrderDir = $criteria->getOrderDirection();
        $this->assertSame($newOrderDir, $updatedOrderDir);
    }

    public function testOrderDirectionOrDefaultGetter()
    {
        $criteria = new ModelCriteria();
        $defaultOrderDir = $criteria->getOrderDirection();
        $this->assertNull($defaultOrderDir);

        $orderDirOrDefault = $criteria->getOrderDirectionOrDefault();
        $this->assertNotNull($orderDirOrDefault);
        $this->assertSame($criteria::DEFAULT_ORDER_DIRECTION, $orderDirOrDefault);
    }
}
