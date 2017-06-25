<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use AppBundle\Handler\Model as ModelHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Siren\Handler;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/models/{uuid}",
     *   summary="Update a model",
     *   tags={"Models"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     description="Model uuid to update",
     *     in="path",
     *     name="uuid",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Model",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/Update"),
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="Model updated"
     *   ),
     *   @SWG\Response(
     *     response="400",
     *     description="Invalid request"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="Model not found"
     *   )
     * )
     * @Route("/models/{uuid}", name="update_model")
     * @Method({"POST"})
     * @ParamConverter("model", class="AppBundle:Model", converter="update_model_converter")
     */
    public function updateAction(Model $model)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $entityManager->persist($model);
        $entityManager->flush();

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
