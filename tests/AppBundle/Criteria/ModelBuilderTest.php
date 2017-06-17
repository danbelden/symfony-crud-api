<?php

namespace Tests\AppBundle\Criteria;

use AppBundle\Criteria\ModelBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ModelBuilderTest extends TestCase
{
    /**
     * @var Request
     */
    private $request;

    public function setup()
    {
        $this->request = new Request();
        $this->request->query->set('name', 'test');
        $this->request->query->set('limit', 10);
        $this->request->query->set('offset', 1);
        $this->request->query->set('orderField', 'name');
        $this->request->query->set('orderDirection', 'DESC');
    }

    public function testPartialBuild()
    {
        $partialRequest = new Request();
        $partialRequest->query->set('name', 'test');

        $modelBuilder = new ModelBuilder();
        $model = $modelBuilder->build($partialRequest);

        $this->assertSame('test', $model->getName());
        $this->assertNull($model->getLimit());
        $this->assertNull($model->getOffset());
        $this->assertNull($model->getOrderField());
        $this->assertNull($model->getOrderDirection());
    }

    public function testBuild()
    {
        $modelBuilder = new ModelBuilder();
        $model = $modelBuilder->build($this->request);

        $this->assertSame('test', $model->getName());
        $this->assertSame(10, $model->getLimit());
        $this->assertSame(1, $model->getOffset());
        $this->assertSame('name', $model->getOrderField());
        $this->assertSame('DESC', $model->getOrderDirection());
    }
}
