<?php

namespace Tests\AppBundle\Handler;

use AppBundle\Criteria\Model as ModelCriteria;
use AppBundle\Entity\Model as ModelEntity;
use AppBundle\Handler\Models as ModelsHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ModelsTest extends KernelTestCase
{
    public function testToDocument()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $router = $container->get('router');
        $modelsHandler = new ModelsHandler($router);

        $modelOne = new ModelEntity();
        $modelOne->setUuid('test1')
            ->setName('test1');

        $modelTwo = new ModelEntity();
        $modelTwo->setUuid('test2')
            ->setName('test2');

        $modelThree = new ModelEntity();
        $modelThree->setUuid('test3')
            ->setName('test3');

        $criteria = new ModelCriteria();
        $criteria->setName('test')
            ->setLimit(3)
            ->setOffset(2);

        $count = 10;
        $models = [
            $modelOne,
            $modelTwo,
            $modelThree
        ];

        $sirenDoc = $modelsHandler->toDocument($criteria, $count, $models);

        $classes = $sirenDoc->getClass();
        $this->assertContains('models', $classes);
        $this->assertContains('collection', $classes);

        $properties = $sirenDoc->getProperties();
        $this->assertSame('test', $properties['criteria']['name']);
        $this->assertSame(3, $properties['criteria']['limit']);
        $this->assertSame(2, $properties['criteria']['offset']);
        $this->assertNotEmpty($properties['totalResults']);

        $entities = $sirenDoc->getEntities();
        $this->assertCount(3, $entities);

        foreach ($entities as $entity) {
            $this->assertContains('model', $entity->getClass());
            $this->assertNotEmpty($entity->getProperties());
            $this->assertCount(1, $entity->getLinks());
        }

        $links = $sirenDoc->getLinks();
        $this->assertCount(3, $links);

        $selfLinks = array_filter($links, function ($link) {
            return in_array('self', $link->getRel(), true);
        });
        $selfLink = array_shift($selfLinks);
        $this->assertContains('self', $selfLink->getRel());
        $this->assertContains('models', $selfLink->getRel());
        $this->assertContains('collection', $selfLink->getRel());
        $this->assertNotEmpty($selfLink->getHref());

        $prevLinks = array_filter($links, function ($link) {
            return in_array('prev', $link->getRel(), true);
        });
        $prevLink = array_shift($prevLinks);
        $this->assertContains('prev', $prevLink->getRel());
        $this->assertContains('models', $prevLink->getRel());
        $this->assertContains('collection', $prevLink->getRel());
        $this->assertNotEmpty($prevLink->getHref());

        $nextLinks = array_filter($links, function ($link) {
            return in_array('next', $link->getRel(), true);
        });
        $nextLink = array_shift($nextLinks);
        $this->assertContains('next', $nextLink->getRel());
        $this->assertContains('models', $nextLink->getRel());
        $this->assertContains('collection', $nextLink->getRel());
        $this->assertNotEmpty($nextLink->getHref());
    }
}
