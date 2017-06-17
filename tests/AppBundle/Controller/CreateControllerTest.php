<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Siren\Handler;

class CreateControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setup()
    {
        $this->client = static::createClient();

        $mockEntityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container = static::$kernel->getContainer();
        $container->set('doctrine.orm.entity_manager', $mockEntityManager);
    }

    public function testCreateEndpointWithAValidRequestBody()
    {
        $content = json_encode(array('name' => 'test'));
        $this->client->request('POST', '/models', array(), array(), array(), $content);

        $response = $this->client->getResponse();

        $responseCode = $response->getStatusCode();
        $this->assertSame(201, $responseCode);

        $responseBody = $response->getContent();

        $handler = new Handler();
        $document = $handler->toDocument($responseBody);

        $classes = $document->getClass();
        $this->assertContains('model', $classes);

        $properties = $document->getProperties();
        $this->assertArrayHasKey('uuid', $properties);
        $this->assertArrayHasKey('name', $properties);
        $this->assertSame('test', $properties['name']);

        $links = $document->getLinks();
        $this->assertCount(1, $links);

        $selfLink = array_shift($links);
        $this->assertContains('self', $selfLink->getRel());
        $this->assertContains('model', $selfLink->getRel());

        $modelUuid = $properties['uuid'];
        $this->assertContains($modelUuid, $selfLink->getHref());
    }

    public function testCreateEndpointWithAnInvalidRequestBody()
    {
        $content = json_encode(array('name' => ''));
        $this->client->request('POST', '/models', array(), array(), array(), $content);

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertSame(400, $statusCode);
    }
}
