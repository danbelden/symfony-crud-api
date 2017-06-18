<?php

namespace Tests\AppBundle\Form;

use AppBundle\Entity\Model;
use AppBundle\Form\Update as UpdateForm;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Form;

class UpdateTest extends KernelTestCase
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
        $newModel->setUuid($uuidString)
            ->setName('test');

        $this->form = $formFactory->create(
            UpdateForm::class,
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
        $preModel = $this->form->getData();
        $this->assertInstanceOf(Model::class, $preModel);
        $this->assertSame('test', $preModel->getName());

        $submittedData = ['name' => 'test2'];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertTrue($isValid);

        $postModel = $this->form->getData();
        $this->assertInstanceOf(Model::class, $postModel);
        $this->assertSame('test2', $postModel->getName());
    }

    public function testFormWithInvalidData()
    {
        $submittedData = ['name' => ''];
        $this->form->submit($submittedData);

        $isValid = $this->form->isValid();
        $this->assertFalse($isValid);
    }
}
