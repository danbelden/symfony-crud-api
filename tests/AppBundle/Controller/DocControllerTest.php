<?php

namespace Tests\AppBundle\Controller;

use DOMDocument;
use DOMXPath;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DocControllerTest extends WebTestCase
{
    const TEMPLATE_PATH = __DIR__ . '/../../../src/AppBundle/Resources/views/doc.html.twig';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var DOMXPath
     */
    private $templateXpath;

    public function setup()
    {
        $this->client = static::createClient();

        $templateDoc = new DOMDocument();
        $templateDoc->loadHTMLFile(self::TEMPLATE_PATH);

        $this->templateXpath = new DOMXPath($templateDoc);
    }

    public function testDocEndpoint()
    {
        $crawler = $this->client->request('GET', '/doc');

        $response = $this->client->getResponse();
        $responseCode = $response->getStatusCode();
        $this->assertSame(200, $responseCode);

        $templatePageTitle = $this->templateXpath->evaluate('//title')->item(0)->nodeValue;
        $pageTitle = $crawler->filterXPath('//title')->text();
        $this->assertSame($templatePageTitle, $pageTitle);

        $numTemplateStylesheets = $this->templateXpath->evaluate('//link')->length;
        $numStylesheets = $crawler->filterXPath('//link')->count();
        $this->assertSame($numTemplateStylesheets, $numStylesheets);

        $numTemplateScripts = $this->templateXpath->evaluate('//script')->length;
        $numScripts = $crawler->filterXPath('//script')->count();
        $this->assertSame($numTemplateScripts, $numScripts);

        $templateAnchorText = $this->templateXpath->evaluate('//a[@id="logo"]')->item(0)->nodeValue;
        $logoAnchorText = $crawler->filterXPath('//a[@id="logo"]')->text();
        $this->assertSame($templateAnchorText, $logoAnchorText);
    }
}
