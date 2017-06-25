<?php

namespace Tests\AppBundle\ParamConverter;

use AppBundle\Entity\Model;
use AppBundle\ParamConverter\Read as ReadParamConverter;
use AppBundle\Repository\Model as ModelRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ReadTest extends TestCase
{
    /**
     * @var ReadParamConverter
     */
    private $readParamConverter;

    public function setup()
    {
        $mockMetadataFactory = $this->getMockBuilder(ClassMetadataFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockMetadataFactory->method('isTransient')
            ->willReturn(false);

        $mockRepository = $this->getMockBuilder(ModelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRepository->expects($this->any())
            ->method('find')
            ->willReturnCallback(function ($uuid) {
                if ($uuid !== 'test-true') {
                    return null;
                }

                $model = new Model();
                $model->setUuid('test-true')
                    ->setName('test');

                return $model;
            });

        $mockEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->method('getMetadataFactory')
            ->willReturn($mockMetadataFactory);

        $mockEntityManager->method('getRepository')
            ->willReturn($mockRepository);

        $mockRegistry = $this->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistry->method('getManagers')
            ->willReturn(['test']);

        $mockRegistry->method('getManagerForClass')
            ->willReturn($mockEntityManager);

        $this->readParamConverter = new ReadParamConverter($mockRegistry);
    }

    public function testSupports()
    {
        $configuration = new ParamConverter([
            'class' => Model::class
        ]);

        $supports = $this->readParamConverter->supports($configuration);

        $this->assertTrue($supports);
    }

    public function testApplyWithValidRequestUuid()
    {
        $pathParams = ['uuid' => 'test-true'];

        $request = new Request([], [], $pathParams);
        $configuration = new ParamConverter([
            'class' => Model::class,
            'name' => 'uuid'
        ]);

        $this->readParamConverter->apply($request, $configuration);

        $model = $request->attributes->get('uuid');
        $this->assertInstanceOf(Model::class, $model);
        $this->assertNotEmpty($model->getUuid());
        $this->assertSame('test', $model->getName());
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage AppBundle\Entity\Model object not found.
     */
    public function testApplyWithInvalidRequestUuid()
    {
        $pathParams = ['uuid' => 'test-false'];

        $request = new Request([], [], $pathParams);
        $configuration = new ParamConverter([
            'class' => Model::class,
            'name' => 'uuid'
        ]);

        $this->readParamConverter->apply($request, $configuration);
    }
}
