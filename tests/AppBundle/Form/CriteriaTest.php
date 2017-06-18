<?php

namespace Tests\AppBundle\Form;

use AppBundle\Criteria\Model as ModelCriteria;
use AppBundle\Form\Criteria as CriteriaForm;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Form;

class CriteriaTest extends KernelTestCase
{
    /**
     * @var Form
     */
    private $form;

    public function setup()
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $this->form = $formFactory->create(
            CriteriaForm::class,
            new ModelCriteria(),
            [
                'csrf_protection' => false
            ]
        );
    }

    public function testFormFields()
    {
        $this->assertSame(5, $this->form->count());

        $hasNameField = $this->form->has('name');
        $this->assertTrue($hasNameField);
    }

    public function testFormWithValidData()
    {
        $submittedData = [
            'name' => 'test',
            'limit' => 1,
            'offset' => 2,
            'orderField' => 'name',
            'orderDirection' => 'DESC'
        ];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertTrue($isValid);

        $criteria = $this->form->getData();
        $this->assertInstanceOf(ModelCriteria::class, $criteria);
        $this->assertSame('test', $criteria->getName());
        $this->assertSame(1, $criteria->getLimit());
        $this->assertSame(2, $criteria->getOffset());
        $this->assertSame('name', $criteria->getOrderField());
        $this->assertSame('DESC', $criteria->getOrderDirection());
    }

    public function testFormWithInvalidData()
    {
        $submittedData = ['name' => ''];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertFalse($isValid);
    }
}
