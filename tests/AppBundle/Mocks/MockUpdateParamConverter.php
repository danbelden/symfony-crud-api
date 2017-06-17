<?php

namespace Tests\AppBundle\Mocks;

use AppBundle\Entity\Model;
use AppBundle\ParamConverter\Update as UpdateParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MockUpdateParamConverter extends UpdateParamConverter
{
    public function supports(ParamConverter $configuration)
    {
        return true;
    }

    protected function getModelForUpdate(Request $request)
    {
        $uuid = $request->get('uuid');
        if (strlen($uuid) !== 36) {
            throw new NotFoundHttpException('No model found');
        }

        $model = new Model();
        $model->setUuid($uuid)
            ->setName('Test');

        return $model;
    }
}
