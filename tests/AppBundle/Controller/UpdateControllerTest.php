<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Siren\Handler;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\AppBundle\Mocks\MockUpdateParamConverter;

class UpdateControllerTest extends WebTestCase
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

        $formFactory = $container->get('form.factory');
        $mockParamConverter = new MockUpdateParamConverter($formFactory);

        $container->set('doctrine.orm.entity_manager', $mockEntityManager);
        $container->set('update_model_param_converter', $mockParamConverter);
    }

    public function testUpdateEndpointWithAValidRequestBody()
    {
        $uuid = Uuid::uuid4();
        $content = json_encode(array('name' => 'new'));
        $url = '/models/' . $uuid->toString();
        $this->client->request('POST', $url, array(), array(), array(), $content);

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
        $this->assertSame('new', $properties['name']);

        $links = $document->getLinks();
        $this->assertCount(1, $links);

        $selfLink = array_shift($links);
        $this->assertContains('self', $selfLink->getRel());
        $this->assertContains('model', $selfLink->getRel());

        $modelUuid = $properties['uuid'];
        $this->assertContains($modelUuid, $selfLink->getHref());
    }

    public function testUpdateEndpointWithAnInvalidUuid()
    {
        $content = json_encode(array('name' => 'test'));
        $url = '/models/test';
        $this->client->request('POST', $url, array(), array(), array(), $content);

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertSame(404, $statusCode);
    }

    public function testUpdateEndpointWithAnInvalidRequestBody()
    {
        $uuid = Uuid::uuid4();
        $content = json_encode(array('name' => ''));
        $url = '/models/' . $uuid->toString();
        $this->client->request('POST', $url, array(), array(), array(), $content);

        $response = $this->client->getResponse();
        $statusCode = $response->getStatusCode();

        $this->assertSame(400, $statusCode);
    }
}
