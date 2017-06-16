<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class Criteria extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'constraints' => array(
                new Type(array(
                    'type'    => 'string',
                    'message' => 'Criteria `name` must be a string'
                )),
                new NotBlank(array(
                    'message' => 'Criteria `name` must not be blank'
                )),
            )
        ))
        ->add('limit', TextType::class, array(
            'constraints' => array(
                new Range(array(
                    'min'            => 1,
                    'max'            => 1000,
                    'invalidMessage' => 'Criteria `limit` must be an integer',
                    'minMessage'     => 'Criteria `limit` must be at least {{ limit }}',
                    'maxMessage'     => 'Criteria `limit` must be at most {{ limit }}',
                ))
            )
        ))
        ->add('offset', TextType::class, array(
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value'   => 0,
                    'message' => 'Criteria `offset` must be greater or equal to {{ compared_value }}.',
                ))
            )
        ));
    }
}
