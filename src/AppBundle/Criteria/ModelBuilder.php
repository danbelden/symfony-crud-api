<?php

namespace AppBundle\Criteria;

use AppBundle\Criteria\Model as ModelCriteria;
use Symfony\Component\HttpFoundation\Request;

class ModelBuilder extends BaseBuilder
{
    /**
     * Method to convert a request object into a model criteria
     *
     * @param Request $request
     * @return ModelCriteria
     */
    public function build(Request $request)
    {
        $criteria = new ModelCriteria();

        $this->appendName($request, $criteria)
            ->appendLimit($request, $criteria)
            ->appendOffset($request, $criteria)
            ->appendOrderField($request, $criteria)
            ->appendOrderDirection($request, $criteria);

        return $criteria;
    }

    /**
     * Method to append to the provided criteria with the name field parameter,
     * if a name param value is present in the given request object
     *
     * @param Request $request
     * @param ModelCriteria &$criteria (Criteria object to update)
     * @return $this
     */
    protected function appendName(Request $request, ModelCriteria &$criteria)
    {
        if (!empty($request->get('name'))) {
            $criteria->setName($request->get('name'));
        }

        return $this;
    }
}
