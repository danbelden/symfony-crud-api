<?php

namespace AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use AppBundle\Form\Create as CreateForm;
use AppBundle\ParamConverter\Base as BaseParamConverter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Create extends BaseParamConverter implements ParamConverterInterface
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * Constructor
     *
     * @param FormFactory $formFactory
     * @param ManagerRegistry $registry
     */
    public function __construct(
        FormFactory $formFactory,
        ManagerRegistry $registry = null
    ) {
        $this->formFactory = $formFactory;
        $this->registry    = $registry;
    }

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

        $form = $this->formFactory->create(
            CreateForm::class,
            $this->getNewModel(),
            [
                'csrf_protection' => false
            ]
        );
        $form->submit($decodedRequestBody);

        if ($form->isValid() === false) {
            $this->throwFormError($form);
        }

        $request->attributes->set($configuration->getName(), $form->getData());
    }

    /**
     * Helper method to parse the request body and return a data array if valid
     *
     * @param Request $request
     * @return array
     * @throws BadRequestHttpException
     */
    protected function getDecodedRequestBody(Request $request)
    {
        $requestBody = $request->getContent();
        if (empty($requestBody)) {
            throw new BadRequestHttpException('Request body was empty');
        }

        $decodedRequestBody = json_decode($requestBody, true);
        if ($decodedRequestBody === false || $decodedRequestBody === null) {
            throw new BadRequestHttpException('Request body was not valid JSON');
        }

        return $decodedRequestBody;
    }

    /**
     * Helper method to convert the first form error into an invalid request
     * (400) HTTP exception to feedback to the client
     *
     * @param Form $form
     * @throws BadRequestHttpException
     */
    protected function throwFormError(Form $form)
    {
        foreach ($form->getErrors() as $error) {
            $errMsg = $error->getMessage();
            throw new BadRequestHttpException($errMsg);
        }

        foreach ($form->getIterator() as $formField) {
            foreach ($formField->getErrors() as $fieldError) {
                $fieldErrMsg = $fieldError->getMessage();
                throw new BadRequestHttpException($fieldErrMsg);
            }
        }

        $defaultErrMsg = 'Invalid submitted data!';
        throw new BadRequestHttpException($defaultErrMsg);
    }

    /**
     * Helper method to create a new model with a new uuid
     *
     * @return Model
     */
    private function getNewModel()
    {
        $newUuid    = Uuid::uuid4();
        $uuidString = $newUuid->toString();

        $newModel = new Model();
        $newModel->setUuid($uuidString);

        return $newModel;
    }
}
