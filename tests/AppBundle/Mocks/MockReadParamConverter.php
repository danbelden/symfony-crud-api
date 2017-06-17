<?php

namespace Tests\AppBundle\Mocks;

use AppBundle\Entity\Model;
use AppBundle\ParamConverter\Read as ReadParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MockReadParamConverter extends ReadParamConverter
{
    public function supports(ParamConverter $configuration)
    {
        return true;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $uuid = $request->get('uuid');
        if (empty($uuid)) {
            throw new LogicException('No request uuid found');
        }

        $class = $configuration->getClass();
        if (strlen($uuid) !== 36) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $class));
        }

        $model = new Model();
        $model->setUuid($uuid)
            ->setName('Test');

        $name = $configuration->getName();
        $request->attributes->set($name, $model);

        return true;
    }
}
