<?php

namespace AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use AppBundle\Form\Update as UpdateForm;
use AppBundle\ParamConverter\Create as CreateParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Update extends CreateParamConverter implements ParamConverterInterface
{
    /**
     * Method to convert the request into a Model entity (If valid)
     *
     * @param Request $request
     * @param ParamConverter $configuration
     * @throws BadRequestHttpException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $decodedRequestBody = $this->getDecodedRequestBody($request);

        $model = $this->getModelForUpdate($request);
        $form = $this->formFactory->create(
            UpdateForm::class,
            $model,
            [
                'csrf_protection' => false
            ]
        );

        // [Hack] Remove form fields that are null to enable partial updates
        foreach ($form->getIterator() as $field) {
            if (!isset($decodedRequestBody[$field->getName()])) {
                $form->remove($field->getName());
            }
        }

        $form->submit($decodedRequestBody);

        if ($form->isValid() === false) {
            $this->throwFormError($form);
        }

        return $request->attributes->set($configuration->getName(), $form->getData());
    }

    /**
     * Helper method to retrieve the model matching the provided uuid
     *
     * @param Request $request
     * @return Model
     * @throws NotFoundHttpException
     */
    protected function getModelForUpdate(Request $request)
    {
        $uuid = $request->attributes->get('uuid');

        $entityManager = $this->registry->getManager();
        $repository    = $entityManager->getRepository(Model::class);

        $model = $repository->find($uuid);
        if (!($model instanceof Model)) {
            $excMsg = sprintf('No model found with uuid `%s`', $uuid);
            throw new NotFoundHttpException($excMsg);
        }

        return $model;
    }
}
