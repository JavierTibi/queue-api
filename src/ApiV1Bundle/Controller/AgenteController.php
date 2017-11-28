<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Request;

class AgenteController extends ApiController
{
    private $agenteServices;

    /**
     * Listado de agentes
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Get("/agentes")
     */
    public function getListAction(Request $request)
    {
        $puntoAtencionId = $request->get('puntoatencion', null);
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->findAllPaginate($puntoAtencionId, (int) $limit, (int) $offset);
    }

    /**
     * Obtiene un agente
     *
     * @param integer $id Identificador único
     * @return mixed
     * @Get("/agentes/{id}")
     */
    public function getItemAction($id)
    {
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->get($id);
    }

    /**
     * Muestra el listado de ventanillas por usuario que no están asignadas
     *
     * @param $id
     * @Get("/agentes/{id}/ventanillas")
     */
    public function getVentanillasByAgente($id)
    {
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->findVentanillasAgente($id);
    }

    /**
     * Asigna una ventanilla a un Agente
     * @param $idVentanilla
     * @Post("/agentes/{idUsuario}/ventanilla/{idVentanilla}")
     */
    public function asignarVentanillaAction($idUsuario, $idVentanilla)
    {
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->asignarVentanilla(
            $idUsuario,
            $idVentanilla,
            function () {
                return $this->respuestaOk('Agente asignado a la ventanilla con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}
