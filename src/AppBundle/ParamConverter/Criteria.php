<?php

namespace AppBundle\ParamConverter;

use AppBundle\Criteria\ModelBuilder as ModelCriteriaBuilder;
use AppBundle\Form\Criteria as CriteriaForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Criteria implements ParamConverterInterface
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var ModelCriteriaBuilder
     */
    protected $criteriaBuilder;

    /**
     * Constructor
     *
     * @param FormFactory $formFactory
     * @param ModelCriteriaBuilder $criteriaBuilder
     */
    public function __construct(
        FormFactory $formFactory,
        ModelCriteriaBuilder $criteriaBuilder
    ) {
        $this->formFactory     = $formFactory;
        $this->criteriaBuilder = $criteriaBuilder;
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
        return true;
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
        $queryParams = $request->query->all();

        $form = $this->formFactory->create(
            CriteriaForm::class,
            null,
            [
                'csrf_protection' => false
            ]
        );

        // [Hack] Remove form fields that are null to enable partial updates
        foreach ($form->getIterator() as $field) {
            if (!isset($queryParams[$field->getName()])) {
                $form->remove($field->getName());
            }
        }

        $form->submit($queryParams);
        if ($form->isValid() === false) {
            $this->throwFormError($form);
        }

        $criteria = $this->criteriaBuilder->build($request);

        $request->attributes->set($configuration->getName(), $criteria);
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
}
