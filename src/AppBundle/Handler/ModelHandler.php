<?php

namespace AppBundle\Handler;

use AppBundle\Entity\Model;
use Siren\Document;
use Siren\Link;
use Symfony\Component\Routing\Router;

class ModelHandler
{
    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor
     *
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Method to convert a model object to a siren document
     *
     * @param Model $model
     * @return Document
     */
    public function toDocument(Model $model)
    {
        $document = new Document();
        $document->setClass(array('model'));

        $document->setProperties(array(
            'uuid' => $model->getUuId(),
            'name' => $model->getName()
        ));

        $href = $this->router->generate(
            'read_one_model',
            array('uuid' => $model->getUuId()),
            Router::ABSOLUTE_URL
        );

        $link = new Link();
        $link->setHref($href);
        $link->setRel(array('self', 'model'));
        $document->setLinks(array($link));

        return $document;
    }
}
