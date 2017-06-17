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

class DeleteController extends Controller
{
    /**
     * @SWG\Delete(
     *   path="/models/{uuid}",
     *   summary="Delete a model",
     *   tags={"Models"},
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     description="Model uuid to delete",
     *     in="path",
     *     name="uuid",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="Model deleted"
     *   ),
     *   @SWG\Response(
     *     response="404",
     *     description="Model not found"
     *   )
     * )
     * @Route("/models/{uuid}", name="delete_model")
     * @Method({"DELETE"})
     * @ParamConverter("model", class="AppBundle:Model", converter="read_model_converter")
     */
    public function deleteAction(Model $model)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $entityManager->remove($model);
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
