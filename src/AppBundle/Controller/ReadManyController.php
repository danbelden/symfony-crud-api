<?php

namespace AppBundle\Controller;

use AppBundle\Criteria\Model as ModelCriteria;
use AppBundle\Entity\Model;
use AppBundle\Handler\ModelsHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReadManyController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/models",
     *   summary="List models",
     *   tags={"Models"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     type="string",
     *     name="name",
     *     in="query",
     *     description="Name of model to retrieve",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     type="integer",
     *     name="limit",
     *     in="query",
     *     description="Number of models to read",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     type="integer",
     *     name="offset",
     *     in="query",
     *     description="Offset to read first result from",
     *     required=false
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="An example resource"
     *   ),
     *   @SWG\Response(
     *     response="400",
     *     description="Invalid request"
     *   )
     * )
     * @Method({"GET"})
     * @Route("/models", name="read_many_model")
     * @ParamConverter("criteria", class="AppBundle:Criteria:Model", converter="create_model_criteria_converter")
     * @param ModelCriteria $criteria
     * @param Request $request
     */
    public function readManyAction(ModelCriteria $criteria, Request $request)
    {
        $modelsRepository = $this->get('doctrine.orm.entity_manager')
            ->getRepository(Model::class);

        $sTime  = microtime(true);
        $count  = $modelsRepository->countByCriteria($criteria);
        $models = $modelsRepository->findByCriteria($criteria);
        $eTime  = microtime(true);
        $tTime  = $eTime - $sTime;

        $router         = $this->get('router');
        $modelsHandler  = new ModelsHandler($router);
        $modelsDocument = $modelsHandler->toDocument($criteria, $count, $models);

        $properties = array(
            'criteria'     => $criteria->toArray(),
            'totalResults' => $count,
            'secondsTaken' => (float) number_format($tTime, 2)
        );
        $modelsDocument->setProperties($properties);

        $documentArray = $modelsDocument->toArray();
        if (empty($documentArray['entities'])) {
            $documentArray['entities'] = array();
        }

        $jsonResponse = new JsonResponse();
        $jsonResponse->setData($documentArray);

        return $jsonResponse;
    }
}
