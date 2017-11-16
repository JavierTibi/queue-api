<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;

class PuntoAtencionController extends ApiController
{
    private $puntoAtencionServices;

    /**
     * Listado de puntos de atencion
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Get("/puntoatencion")
     */
    public function getListAction(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $this->puntoAtencionServices = $this->getPuntosAtencionService();
        return $this->puntoAtencionServices->findAllPaginate((int) $limit, (int) $offset);
    }
}
