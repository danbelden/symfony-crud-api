<?php

namespace AppBundle\Handler;

use AppBundle\Criteria\Model as ModelCriteria;
use AppBundle\Entity\Model;
use AppBundle\Paginator\LimitOffsetHelper;
use Siren\Document;
use Siren\Entity;
use Siren\Link;
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
     * Method to convert an array of model objects; to  a siren document object
     *
     * @param ModelCriteria $criteria
     * @param int $count
     * @param array $models
     * @return Document
     */
    public function toDocument(ModelCriteria $criteria, $count, array $models)
    {
        $document = new Document();
        $document->setClass(array('models', 'collection'));

        $entities = $this->getEntities($models);
        $document->setEntities($entities);

        $links = $this->getLinks($criteria, $count);
        $document->setLinks($links);

        return $document;
    }

    /**
     * Helper method to convert model objects into entity objects
     *
     * @param array $models
     * @return array
     */
    private function getEntities(array $models)
    {
        $entities = array();

        foreach ($models as $model) {
            assert($model instanceof Model);
            $entities[] = $this->getEntity($model);
        }

        return $entities;
    }

    /**
     * Helper method to create an entity object from a model object
     *
     * @param Model $model
     * @return Entity
     */
    private function getEntity(Model $model)
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

    /**
     * Helper method to build the links array from the criteria and match count
     *
     * @param ModelCriteria $criteria
     * @param int $count
     * @return array
     */
    private function getLinks(ModelCriteria $criteria, $count)
    {
        $links = array();

        $links[] = $this->getSelfLink($criteria);

        $prevLink = $this->getPrevLink($criteria, $count);
        if ($prevLink instanceof Link) {
            $links[] = $prevLink;
        }

        $nextLink = $this->getNextLink($criteria, $count);
        if ($nextLink instanceof Link) {
            $links[] = $nextLink;
        }

        return $links;
    }

    /**
     * Helper method to generate a self referencing link to this page of the
     * models collection. Converts the criteria object into a url effectively.
     *
     * @param ModelCriteria $criteria
     * @return string
     */
    private function getSelfLink(ModelCriteria $criteria)
    {
        $params = $criteria->toArray();

        $linkHref = $this->router->generate(
            'read_many_model',
            $params,
            Router::ABSOLUTE_URL
        );

        $link = new Link();
        $link->setHref($linkHref);
        $link->setRel(array('self', 'models', 'collection'));

        return $link;
    }

    /**
     * Helper method to create a link to the previous result set (pagination)
     *
     * @param ModelCriteria $criteria
     * @param int $count
     * @return Link
     */
    private function getPrevLink(ModelCriteria $criteria, $count)
    {
        $limit = $criteria->getLimitOrDefault();
        $offset = $criteria->getOffsetOrDefault();

        $limOffHelper = new LimitOffsetHelper();
        $prevLimit = $limOffHelper->getPreviousLimit($limit, $offset, $count);
        $prevOffset = $limOffHelper->getPreviousOffset($limit, $offset, $count);
        if ($prevLimit === null || $prevOffset === null) {
            return null;
        }

        $params = $criteria->toArray();
        $params['limit'] = $prevLimit;
        $params['offset'] = $prevOffset;

        $linkHref = $this->router->generate(
            'read_many_model',
            $params,
            Router::ABSOLUTE_URL
        );

        $link = new Link();
        $link->setHref($linkHref);
        $link->setRel(array('prev', 'models', 'collection'));

        return $link;
    }

    /**
     * Helper method to create a link to the following result set (pagination)
     *
     * @param ModelCriteria $criteria
     * @param int $count
     * @return Link
     */
    private function getNextLink(ModelCriteria $criteria, $count)
    {
        $limit = $criteria->getLimitOrDefault();
        $offset = $criteria->getOffsetOrDefault();

        $limOffHelper = new LimitOffsetHelper();
        $nextLimit = $limOffHelper->getNextLimit($limit, $offset, $count);
        $nextOffset = $limOffHelper->getNextOffset($limit, $offset, $count);
        if ($nextLimit === null || $nextOffset === null) {
            return null;
        }

        $params = $criteria->toArray();
        $params['limit'] = $nextLimit;
        $params['offset'] = $nextOffset;

        $linkHref = $this->router->generate(
            'read_many_model',
            $params,
            Router::ABSOLUTE_URL
        );

        $link = new Link();
        $link->setHref($linkHref);
        $link->setRel(array('next', 'models', 'collection'));

        return $link;
    }
}
