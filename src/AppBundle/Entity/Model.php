<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Model")
 * @ORM\Table(name="models")
 */
class Model
{
    /**
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuId()
    {
        return $this->uuid;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     * @return Model
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

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
     * @return Model
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
