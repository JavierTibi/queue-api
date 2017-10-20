<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends ApiController
{

    /**
     * Controller por defecto
     *
     * @return JsonResponse
     * @Get("/", name="api_index")
     */
    public function indexAction()
    {
        return new JsonResponse([]);
    }
}
