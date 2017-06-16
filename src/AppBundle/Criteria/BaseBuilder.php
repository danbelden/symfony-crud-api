<?php

namespace AppBundle\Criteria;

use AppBundle\Criteria\Base as BaseCriteria;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseBuilder
{
    /**
     * Method to convert a request object into a criteria
     *
     * @param Request $request
     * @return BaseCriteria
     */
    abstract public function build(Request $request);

    /**
     * Method to append to the provided criteria with the limit parameter, if
     * a limit param value is present in the given request object
     *
     * @param Request $request
     * @param BaseCriteria &$criteria (Criteria object to update)
     * @return $this
     */
    protected function appendLimit(Request $request, BaseCriteria &$criteria)
    {
        if (is_numeric($request->get('limit'))) {
            $criteria->setLimit((int)$request->get('limit'));
        }

        return $this;
    }

    /**
     * Method to append to the provided criteria with the offset parameter, if
     * an offset param value is present in the given request object
     *
     * @param Request $request
     * @param BaseCriteria &$criteria (Criteria object to update)
     * @return $this
     */
    protected function appendOffset(Request $request, BaseCriteria &$criteria)
    {
        if (is_numeric($request->get('offset'))) {
            $criteria->setOffset((int)$request->get('offset'));
        }

        return $this;
    }

    /**
     * Method to append to the provided criteria with the order field parameter,
     * if an orderField param value is present in the given request object
     *
     * @param Request $request
     * @param BaseCriteria &$criteria (Criteria object to update)
     * @return $this
     */
    protected function appendOrderField(Request $request, BaseCriteria &$criteria)
    {
        if (!empty($request->get('orderField'))) {
            $criteria->setOrderField($request->get('orderField'));
        }

        return $this;
    }

    /**
     * Method to append to the provided criteria with the order direction
     * parameter, if an orderDirection param value is present in the given
     * request object
     *
     * @param Request $request
     * @param BaseCriteria &$criteria (Criteria object to update)
     * @return $this
     */
    protected function appendOrderDirection(Request $request, BaseCriteria &$criteria)
    {
        if (!empty($request->get('orderDirection'))) {
            $criteria->setOrderDirection($request->get('orderDirection'));
        }

        return $this;
    }
}
