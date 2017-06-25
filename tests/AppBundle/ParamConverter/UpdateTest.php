<?php

namespace Tests\AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use AppBundle\ParamConverter\Update as UpdateParamConverter;
use AppBundle\Repository\Model as ModelRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateTest extends KernelTestCase
{
    /**
     * @var UpdateParamConverter
     */
    private $updateParamConverter;

    public function setup()
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $mockRepository = $this->getMockBuilder(ModelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRepository->method('find')
            ->willReturnCallback(function ($uuid) {
                if ($uuid !== 'test-true') {
                    return null;
                }

                $model = new Model();
                $model->setUuid('test-true')
                    ->setName('test');

                return $model;
            });

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

        $mockEntityManager->method('getRepository')
            ->willReturn($mockRepository);

        $mockRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistry->method('getManager')
            ->willReturn($mockEntityManager);

        $mockRegistry->method('getManagers')
            ->willReturn(['test']);

        $mockRegistry->method('getManagerForClass')
            ->willReturn($mockEntityManager);

        $this->updateParamConverter = new UpdateParamConverter(
            $formFactory,
            $mockRegistry
        );
    }

    public function testSupports()
    {
        $configuration = new ParamConverter([
            'class' => Model::class
        ]);

        $supports = $this->updateParamConverter->supports($configuration);

        $this->assertTrue($supports);
    }

    public function testApplyWithValidUuidAndRequestBody()
    {
        $pathParams = ['uuid' => 'test-true'];
        $requestBody = ['name' => 'update'];
        $requestBodyJson = json_encode($requestBody);

        $request = new Request([], [], $pathParams, [], [], [], $requestBodyJson);
        $configuration = new ParamConverter([
            'name' => Model::class
        ]);

        $this->updateParamConverter->apply($request, $configuration);

        $model = $request->attributes->get(Model::class);
        $this->assertInstanceOf(Model::class, $model);
        $this->assertNotEmpty($model->getUuid());
        $this->assertSame('update', $model->getName());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Model `name` must not be blank
     */
    public function testApplyWithValidUuidAndInvalidRequestBody()
    {
        $pathParams = ['uuid' => 'test-true'];
        $requestBody = ['name' => ''];
        $requestBodyJson = json_encode($requestBody);

        $request = new Request([], [], $pathParams, [], [], [], $requestBodyJson);
        $configuration = new ParamConverter([
            'name' => Model::class
        ]);

        $this->updateParamConverter->apply($request, $configuration);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage No model found with uuid `test-false`
     */
    public function testApplyWithInvalidUuid()
    {
        $pathParams = ['uuid' => 'test-false'];
        $requestBody = ['name' => ''];
        $requestBodyJson = json_encode($requestBody);

        $request = new Request([], [], $pathParams, [], [], [], $requestBodyJson);
        $configuration = new ParamConverter([
            'name' => Model::class
        ]);

        $this->updateParamConverter->apply($request, $configuration);
    }
}
