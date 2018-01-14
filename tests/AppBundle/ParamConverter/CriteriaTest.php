<?php

namespace Tests\AppBundle\ParamConverter;

use AppBundle\Criteria\Model as ModelCriteria;
use AppBundle\Criteria\ModelBuilder as ModelCriteriaBuilder;
use AppBundle\ParamConverter\Criteria as CriteriaParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class CriteriaTest extends KernelTestCase
{
    /**
     * @var CriteriaParamConverter
     */
    private $criteriaParamConverter;

    public function setup()
    {
        self::bootKernel();

        $container = self::$kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $criteriaBuilder = new ModelCriteriaBuilder();

        $this->criteriaParamConverter = new CriteriaParamConverter(
            $formFactory,
            $criteriaBuilder
        );
    }

    public function testSupports()
    {
        $configuration = new ParamConverter([
            'class' => Model::class
        ]);

        $supports = $this->criteriaParamConverter->supports($configuration);

        $this->assertTrue($supports);
    }

    public function testApplyWithPartialValidRequestFilters()
    {
        $getParams = [
            'name' => 'test',
            'limit' => 1
        ];

        $request = new Request($getParams);
        $configuration = new ParamConverter([
            'name' => ModelCriteria::class
        ]);

        $this->criteriaParamConverter->apply($request, $configuration);

        $criteria = $request->attributes->get(ModelCriteria::class);
        $this->assertInstanceOf(ModelCriteria::class, $criteria);
        $this->assertSame('test', $criteria->getName());
        $this->assertSame(1, $criteria->getLimit());
        $this->assertNull($criteria->getOffset());
        $this->assertNull($criteria->getOrderField());
        $this->assertNull($criteria->getOrderDirection());
    }

    public function testApplyWithFullValidRequestFilters()
    {
        $getParams = [
            'name' => 'test',
            'limit' => 1,
            'offset' => 2,
            'orderField' => 'name',
            'orderDirection' => 'DESC'
        ];

        $request = new Request($getParams);
        $configuration = new ParamConverter([
            'name' => ModelCriteria::class
        ]);

        $this->criteriaParamConverter->apply($request, $configuration);

        $criteria = $request->attributes->get(ModelCriteria::class);
        $this->assertInstanceOf(ModelCriteria::class, $criteria);
        $this->assertSame('test', $criteria->getName());
        $this->assertSame(1, $criteria->getLimit());
        $this->assertSame(2, $criteria->getOffset());
        $this->assertSame('name', $criteria->getOrderField());
        $this->assertSame('DESC', $criteria->getOrderDirection());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage This form should not contain extra fields.
     */
    public function testApplyWithInvalidRequestFilter()
    {
        $getParams = ['num' => ''];

        $request = new Request($getParams);
        $configuration = new ParamConverter([
            'name' => ModelCriteria::class
        ]);

        $this->criteriaParamConverter->apply($request, $configuration);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Criteria `name` must not be blank
     */
    public function testApplyWithInvalidRequestFilterValue()
    {
        $getParams = ['name' => ''];

        $request = new Request($getParams);
        $configuration = new ParamConverter([
            'name' => ModelCriteria::class
        ]);

        $this->criteriaParamConverter->apply($request, $configuration);
    }
}
