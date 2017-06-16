<?php

namespace AppBundle\Criteria;

use AppBundle\Criteria\Base as BaseCriteria;

class Model extends BaseCriteria
{
    /**
     * @var string
     */
    private $name;

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
