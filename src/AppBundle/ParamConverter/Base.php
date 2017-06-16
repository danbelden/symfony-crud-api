<?php

namespace AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Base implements ParamConverterInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    /**
     * Method to determine if this param converter supports this param
     * converter configuration
     *
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        // If there is no manager, this means that only Doctrine DBAL is configured
        // In this case we can do nothing and just return
        if (
            $this->registry === null ||
            count($this->registry->getManagers()) === 0
        ) {
            return false;
        }

        // Check, if option class was set in configuration
        $configClass = $configuration->getClass();
        if ($configClass === null) {
            return false;
        }

        // Check, if entity manager for model is defined
        $entityManager   = $this->registry->getManagerForClass($configClass);
        $entityNamespace = $entityManager->getClassMetadata($configClass)->getName();
        if (Model::class !== $entityNamespace) {
            return false;
        }

        // Return true as all tests passed
        return true;
    }

    /**
     * Method to convert the incoming request into the relevant object
     *
     * @param Request $request
     * @param ParamConverter $configuration
     * @return mixed
     */
    abstract public function apply(Request $request, ParamConverter $configuration);
}
