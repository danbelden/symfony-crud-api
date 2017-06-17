<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Handler\ModelHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Siren\Handler;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReadOneController extends Controller
{
    /**
     * @SWG\Get(
     *   path="/models/{uuid}",
     *   summary="Fetch a model",
     *   tags={"Models"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     description="Model uuid to fetch",
     *     in="path",
     *     name="uuid",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="Model found"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="Model not found"
     *   )
     * )
     * @Method({"GET"})
     * @ParamConverter("model", class="AppBundle:Model", converter="read_model_converter")
     * @Route("/models/{uuid}", name="read_one_model")
     * @param Model $model
     */
    public function readOneAction(Model $model)
    {
        $router        = $this->get('router');
        $modelHandler  = new ModelHandler($router);
        $modelDocument = $modelHandler->toDocument($model);

        $sirenHandler = new Handler();
        $jsonString   = $sirenHandler->toJson($modelDocument);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($jsonString);

        return $jsonResponse;
    }
}
