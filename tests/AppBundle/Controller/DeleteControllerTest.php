<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Siren\Handler;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Mocks\MockReadParamConverter;

class DeleteControllerTest extends WebTestCase
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

        $mockParamConverter = new MockReadParamConverter();

        $container = static::$kernel->getContainer();
        $container->set('doctrine.orm.entity_manager', $mockEntityManager);
        $container->set('read_model_param_converter', $mockParamConverter);
    }

    public function testDeleteEndpointWithAValidModelUuid()
    {
        $uuid = Uuid::uuid4();
        $url = '/models/' . $uuid->toString();
        $this->client->request('DELETE', $url);

        $response = $this->client->getResponse();
        $responseCode = $response->getStatusCode();
        $this->assertSame(200, $responseCode);

        $responseBody = $response->getContent();

        $handler = new Handler();
        $document = $handler->toDocument($responseBody);

        $classes = $document->getClass();
        $this->assertContains('model', $classes);

        $properties = $document->getProperties();
        $this->assertArrayHasKey('uuid', $properties);
        $this->assertArrayHasKey('name', $properties);
        $this->assertSame($uuid->toString(), $properties['uuid']);

        $links = $document->getLinks();
        $this->assertCount(1, $links);

        $selfLink = array_shift($links);
        $this->assertContains('self', $selfLink->getRel());
        $this->assertContains('model', $selfLink->getRel());

        $this->assertContains($uuid->toString(), $selfLink->getHref());
    }

    public function testDeleteEndpointWithAnInvalidModelUuid()
    {
        $this->client->request('DELETE', '/models/test');

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertSame(404, $statusCode);
    }
}
