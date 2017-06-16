<?php

namespace AppBundle\Request;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class Update
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $name;
}
