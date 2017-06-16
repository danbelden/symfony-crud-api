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

class CreateController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/models",
     *   summary="Create a model",
     *   tags={"Models"},
     *   consumes={"application/json"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Model",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Create"),
     *   ),
     *   @SWG\Response(
     *     response="201",
     *     description="Model created"
     *   ),
     *   @SWG\Response(
     *     response="400",
     *     description="Invalid request"
     *   )
     * )
     * @Route("/models", name="create_model")
     * @ParamConverter("model", class="AppBundle:Model", converter="create_model_converter")
     * @Method({"POST"})
     */
    public function createAction(Model $model)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($model);
        $entityManager->flush();

        $router        = $this->get('router');
        $modelHandler  = new ModelHandler($router);
        $modelDocument = $modelHandler->toDocument($model);

        $sirenHandler = new Handler();
        $sirenJson    = $sirenHandler->toJson($modelDocument);

        $jsonResponse = new JsonResponse();
        $jsonResponse->setContent($sirenJson);

        return $jsonResponse;
    }
}
