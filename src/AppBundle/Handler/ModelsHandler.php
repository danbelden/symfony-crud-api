<?php

namespace AppBundle\Handler;

use AppBundle\Entity\Model;
use Siren\Document;
use Siren\Entity;
use Siren\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ModelsHandler
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
     * Method to convert a model object to a collection of model objects to a
     * siren document
     *
     * @param Request $request
     * @param array $models
     * @return Document
     */
    public function toDocument(Request $request, array $models)
    {
        $document = new Document();
        $document->setClass(array('model'));

        foreach ($models as $model) {
            assert($model instanceof Model);
            $entity = $this->getEntity($model);
            $document->addEntity($entity);
        }

        $link = new Link();
        $link->setHref($this->getSelfHref($request));
        $link->setRel(array('self', 'collection'));
        $document->addLink($link);

        return $document;
    }

    /**
     * Helper method to generate a self referencing link to the collection
     *
     * @param Request $request
     * @return string
     */
    protected function getSelfHref(Request $request)
    {
        return $this->router->generate(
            'read_many_model',
            array(),
            Router::ABSOLUTE_URL
        );
    }

    /**
     * Helper method to create an entity from a model
     *
     * @param Model $model
     * @return Entity
     */
    protected function getEntity(Model $model)
    {
        $entity = new Entity();
        $entity->setClass(array('model'));
        $entity->setProperties(array(
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
        $entity->setLinks(array($link));

        return $entity;
    }
}
