<?php

namespace Tests\AppBundle\Form;

use AppBundle\Entity\Model;
use AppBundle\Form\Create as CreateForm;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Form;

class CreateTest extends KernelTestCase
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

        $newUuid    = Uuid::uuid4();
        $uuidString = $newUuid->toString();

        $newModel = new Model();
        $newModel->setUuid($uuidString);

        $this->form = $formFactory->create(
            CreateForm::class,
            $newModel,
            [
                'csrf_protection' => false
            ]
        );
    }

    public function testFormFields()
    {
        $this->assertSame(1, $this->form->count());

        $hasNameField = $this->form->has('name');
        $this->assertTrue($hasNameField);
    }

    public function testFormWithValidData()
    {
        $submittedData = ['name' => 'test'];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertTrue($isValid);

        $model = $this->form->getData();
        $this->assertInstanceOf(Model::class, $model);
        $this->assertSame('test', $model->getName());
    }

    public function testFormWithInvalidData()
    {
        $submittedData = ['name' => ''];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertFalse($isValid);
    }
}
