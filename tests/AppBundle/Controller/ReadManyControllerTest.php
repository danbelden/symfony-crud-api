<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Repository\Model as ModelRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Siren\Handler;

class ReadManyControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setup()
    {
        $this->client = static::createClient();

        $mockRepository = $this->getMockBuilder(ModelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockRepository->method('countByCriteria')
            ->willReturn(9);

        $mockModelOne = new Model();
        $mockModelOne->setUuid('1')
            ->setName('test');

        $mockModelTwo = new Model();
        $mockModelTwo->setUuid('2')
            ->setName('test');

        $mockModelThree = new Model();
        $mockModelThree->setUuid('3')
            ->setName('test');

        $mockRepository->method('findByCriteria')
            ->willReturn([$mockModelOne, $mockModelTwo, $mockModelThree]);

        $mockEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->method('getRepository')
            ->willReturn($mockRepository);

        $container = static::$kernel->getContainer();
        $container->set('doctrine.orm.entity_manager', $mockEntityManager);
    }

    public function testReadManyEndpointWithValidParams()
    {
        $this->client->request('GET', '/models?name=test&limit=3&offset=3');

        $response = $this->client->getResponse();
        $responseBody = $response->getContent();

        $responseCode = $response->getStatusCode();
        $this->assertSame(200, $responseCode);

        $handler = new Handler();
        $document = $handler->toDocument($responseBody);

        $classes = $document->getClass();
        $this->assertContains('models', $classes);
        $this->assertContains('collection', $classes);

        $properties = $document->getProperties();
        $this->assertArrayHasKey('criteria', $properties);
        $this->assertArrayHasKey('totalResults', $properties);
        $this->assertArrayHasKey('secondsTaken', $properties);

        $this->assertArrayHasKey('criteria', $properties);
        $this->assertArrayHasKey('totalResults', $properties);
        $this->assertArrayHasKey('secondsTaken', $properties);

        $this->assertSame('test', $properties['criteria']['name']);
        $this->assertSame(3, $properties['criteria']['limit']);
        $this->assertSame(3, $properties['criteria']['offset']);

        $entities = $document->getEntities();
        $this->assertCount(3, $entities);
        foreach($entities as $entity) {
            $properties = $entity->getProperties();
            $this->assertSame('test', $properties['name']);

            $links = $entity->getLinks();
            $selfLink = array_shift($links);
            $selfLinkRels = $selfLink->getRel();
            $this->assertContains('model', $selfLinkRels);
            $this->assertContains('self', $selfLinkRels);
        }

        $links = $document->getLinks();
        $this->assertCount(3, $links);
        foreach ($links as $link) {
            $rels = $link->getRel();
            $this->assertContains('collection', $rels);
            $this->assertContains('models', $rels);
        }
    }

    public function testReadManyEndpointWithInvalidParams()
    {
        $this->client->request('GET', '/models?test=test');

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertSame(400, $statusCode);
    }
}
