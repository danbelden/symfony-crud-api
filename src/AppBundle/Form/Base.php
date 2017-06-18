<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

abstract class Base extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('limit', TextType::class, [
            'constraints' => [
                new Range([
                    'min'            => 1,
                    'max'            => 1000,
                    'invalidMessage' => 'Criteria `limit` must be an integer',
                    'minMessage'     => 'Criteria `limit` must be at least {{ limit }}',
                    'maxMessage'     => 'Criteria `limit` must be at most {{ limit }}',
                ])
            ]
        ])
        ->add('offset', TextType::class, [
            'constraints' => [
                new GreaterThanOrEqual([
                    'value'   => 0,
                    'message' => 'Criteria `offset` must be greater or equal to {{ compared_value }}.',
                ])
            ]
        ])
        ->add('orderField', TextType::class, [
            'constraints' => [
                new Choice([
                    'choices' => ['name'],
                    'message' => 'Criteria `orderField` is not a valid choice.',
                ])
            ]
        ])
        ->add('orderDirection', TextType::class, [
            'constraints' => [
                new Choice([
                    'choices' => ['ASC', 'DESC'],
                    'message' => 'Criteria `orderDirection` is not a valid choice.',
                ])
            ]
        ]);
    }
}
