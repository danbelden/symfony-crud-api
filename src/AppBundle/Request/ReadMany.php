<?php

namespace AppBundle\Request;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class ReadMany
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $name;

    /**
     * @SWG\Property()
     * @var int
     */
    public $limit;

    /**
     * @SWG\Property()
     * @var int
     */
    public $offset;

    /**
     * @SWG\Property()
     * @var string
     */
    public $sortField;

    /**
     * @SWG\Property()
     * @var string
     */
    public $sortDirection;
}
