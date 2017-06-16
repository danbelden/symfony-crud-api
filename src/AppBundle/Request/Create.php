<?php

namespace AppBundle\Request;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *   required={"name"},
 * )
 */
class Create
{
    /**
     * @SWG\Property()
     * @var string
     */
    public $name;
}
