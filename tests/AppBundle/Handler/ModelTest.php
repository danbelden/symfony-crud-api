<?php

namespace Tests\AppBundle\Handler;

use AppBundle\Entity\Model as ModelEntity;
use AppBundle\Handler\Model as ModelHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ModelTest extends KernelTestCase
{
    public function testToDocument()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $router = $container->get('router');
        $modelHandler = new ModelHandler($router);

        $model = new ModelEntity();
        $model->setUuid('test')
            ->setName('test2');

        $sirenDoc = $modelHandler->toDocument($model);

        $classes = $sirenDoc->getClass();
        $this->assertContains('model', $classes);

        $properties = $sirenDoc->getProperties();
        $this->assertSame('test', $properties['uuid']);
        $this->assertSame('test2', $properties['name']);

        $links = $sirenDoc->getLinks();
        $this->assertCount(1, $links);

        $selfLink = array_shift($links);
        $this->assertContains('self', $selfLink->getRel());
        $this->assertContains('model', $selfLink->getRel());
        $this->assertContains($properties['uuid'], $selfLink->getHref());
    }
}
