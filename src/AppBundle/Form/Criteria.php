<?php

namespace AppBundle\Form;

use AppBundle\Form\Base as BaseForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class Criteria extends BaseForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'constraints' => [
                new Type([
                    'type'    => 'string',
                    'message' => 'Criteria `name` must be a string'
                ]),
                new NotBlank([
                    'message' => 'Criteria `name` must not be blank'
                ]),
            ]
        ]);
        parent::buildForm($builder, $options);
    }
}
