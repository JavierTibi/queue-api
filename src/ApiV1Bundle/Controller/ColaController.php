<?php

namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ColaController extends ApiController
{
    private $colaServices;

    /**
     * Listado de colas
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Get("/colas")
     */
    public function getListAction(Request $request)
    {
        $puntoAtencionId = $request->get('puntoatencion');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $this->colaServices = $this->getColasServices();
        return $this->colaServices->findAllPaginate($puntoAtencionId, (int) $limit, (int) $offset);
    }

    /**
     * Obtiene una cola
     *
     * @param integer $id Identificador único
     * @return mixed
     * @Get("/colas/{id}")
     */
    public function getItemAction($id)
    {
        $this->colaServices = $this->getColasServices();
        return $this->colaServices->get($id);
    }

    /**
     * Crea una cola de un grupo de tramite
     * @param Request $request
     * @Post("/colas/grupotramite")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->colaServices = $this->getColasServices();

        return $this->colaServices->addColaGrupoTramite(
            $params,
            function ($cola) {
                return $this->respuestaOk('Cola agregada con éxito', [
                    'id' => $cola->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Eliminar una cola de grupo de tramite
     *
     * @param integer $id Identificador único de la ventanilla
     * @return mixed
     * @Delete("/colas/grupotramite/{id}")
     */
    public function deleteAction($id)
    {
        $this->colaServices = $this->getColasServices();
        return $this->colaServices->removeColaGrupoTramite(
            $id,
            function () {
                return $this->respuestaOk('Cola eliminada con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Modificar una cola
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Put("/colas/grupotramite/{id}")
     */
    public function putAction(Request $request, $id)
    {
        $params = $request->request->all();
        $this->colaServices = $this->getColasServices();

        return $this->colaServices->editColaGrupoTramite(
            $params,
            $id,
            function () {
                return $this->respuestaOk('Cola modificada con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}
