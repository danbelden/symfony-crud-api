<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocController extends Controller
{
    /**
     * @SWG\Swagger(
     *   @SWG\Info(
     *     version="1.0.0",
     *     title="Model API"
     *   )
     * )
     * @Route("/doc", name="api_doc")
     * @Method({"GET"})
     */
    public function docAction()
    {
        $swaggerFile = $this->getParameter('swagger_file');

        $environment = $this->getParameter('kernel.environment');
        if ($environment === 'dev') {
            $srcDir = rtrim($this->getParameter('src_dir'), DIRECTORY_SEPARATOR);
            $webDir = rtrim($this->getParameter('web_dir'), DIRECTORY_SEPARATOR);
            $swagger = \Swagger\scan($srcDir);
            $swagger->saveAs($webDir . $swaggerFile);
        }

        return $this->render(
            'AppBundle::doc.html.twig',
            ['specFilePath' => $swaggerFile]
        );
    }
}
