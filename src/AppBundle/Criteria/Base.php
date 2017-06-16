<?php

namespace AppBundle\Criteria;

abstract class Base
{
    const DEFAULT_LIMIT = 1000;
    const DEFAULT_OFFSET = 0;
    const DEFAULT_ORDER_DIRECTION = 'ASC';

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var string
     */
    protected $orderField;

    /**
     * @var string
     */
    protected $orderDirection;

    /**
     * Get limit
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Get limit (Or default if it is null)
     *
     * @return int
     */
    public function getLimitOrDefault()
    {
        $limit = $this->getLimit();

        return $limit !== null ? $limit : $this::DEFAULT_LIMIT;
    }

    /**
     * Set limit
     *
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get offset
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Get offset (Or default if it is null)
     *
     * @return int
     */
    public function getOffsetOrDefault()
    {
        $offset = $this->getOffset();

        return $offset !== null ? $offset : $this::DEFAULT_OFFSET;
    }

    /**
     * Set offset
     *
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get order field
     *
     * @return string
     */
    public function getOrderField()
    {
        return $this->orderField;
    }

    /**
     * Set order field
     *
     * @param string $orderField
     * @return $this
     */
    public function setOrderField($orderField)
    {
        $this->orderField = $orderField;

        return $this;
    }

    /**
     * Get order direction
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * Get order direction (Or default if it is null)
     *
     * @return string
     */
    public function getOrderDirectionOrDefault()
    {
        $orderDir = $this->getOrderDirection();

        return $orderDir !== null ? $orderDir : $this::DEFAULT_ORDER_DIRECTION;
    }

    /**
     * Set order direction
     *
     * @param string $orderDirection
     * @return $this
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;

        return $this;
    }

    /**
     * Method to return the criteria object as an array
     * - Only returns properties if they have a defined value (Not null)
     *
     * @return array
     */
    public function toArray()
    {
        $objVars = get_object_vars($this);

        $filteredVars = array_filter($objVars, function ($value) {
            return $value !== null;
        });

        return $filteredVars;
    }
}
