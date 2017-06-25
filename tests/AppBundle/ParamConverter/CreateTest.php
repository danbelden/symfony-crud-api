<?php

namespace Tests\AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use AppBundle\ParamConverter\Create as CreateParamConverter;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateTest extends KernelTestCase
{
    /**
     * @var CreateParamConverter
     */
    private $createParamConverter;

    public function setup()
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $mockMetadata = $this->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockMetadata->method('getName')
            ->willReturn(Model::class);

        $mockEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->method('getClassMetadata')
            ->willReturn($mockMetadata);

        $mockRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistry->method('getManagers')
            ->willReturn(['test']);

        $mockRegistry->method('getManagerForClass')
            ->willReturn($mockEntityManager);

        $this->createParamConverter = new CreateParamConverter(
            $formFactory,
            $mockRegistry
        );
    }

    public function testSupports()
    {
        $configuration = new ParamConverter([
            'class' => Model::class
        ]);

        $supports = $this->createParamConverter->supports($configuration);

        $this->assertTrue($supports);
    }

    public function testApplyWithValidRequestBody()
    {
        $requestBody = ['name' => 'test'];
        $requestBodyJson = json_encode($requestBody);

        $request = new Request([], [], [], [], [], [], $requestBodyJson);
        $configuration = new ParamConverter([
            'name' => Model::class
        ]);

        $this->createParamConverter->apply($request, $configuration);

        $model = $request->attributes->get(Model::class);
        $this->assertInstanceOf(Model::class, $model);
        $this->assertNotEmpty($model->getUuid());
        $this->assertSame('test', $model->getName());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Model `name` must not be blank
     */
    public function testApplyWithInvalidRequestBody()
    {
        $requestBody = ['name' => ''];
        $requestBodyJson = json_encode($requestBody);

        $request = new Request([], [], [], [], [], [], $requestBodyJson);
        $configuration = new ParamConverter([
            'name' => Model::class
        ]);

        $this->createParamConverter->apply($request, $configuration);
    }
}
