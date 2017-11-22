<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:01 PM
 */
namespace ApiV1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TurnoController extends ApiController
{
    private $turnoServices;

    /**
     * Guardar Turno
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/turnos")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->turnoServices = $this->getTurnoServices();

        return $this->turnoServices->create(
            $params,
            function ($usuario) {
                return $this->respuestaOk('Turno guardado con éxito', [
                    'id' => $usuario->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Cambiar estado Turno
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/turnos/estado")
     */
    public function cambiarEstadoAction(Request $request)
    {
        $params = $request->request->all();
        $this->turnoServices = $this->getTurnoServices();

        return $this->turnoServices->changeStatus(
            $params,
            function ($usuario) {
                return $this->respuestaOk('Turno modificado con éxito', [
                    'id' => $usuario->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Listado de turnos del Sistema Nacional de Turnos
     *
     * @param Request $request
     * @return mixed
     * @Get("/snt/turnos")
     */
    public function getTurnosSNTAction(Request $request)
    {
        $params = $request->query->all();
        $this->turnoServices = $this->getTurnoServices();
        return $this->turnoServices->getListTurnosSNT(
            $params,
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Busqueda de turnos por código en el Sistema Nacional de Turnos
     *
     * @param Request $request
     * @return mixed
     * @Get("/snt/turnos/buscar")
     */
    public function searchTurnosSNTAction(Request $request)
    {
        $params = $request->query->all();
        $this->turnoServices = $this->getTurnoServices();
        return $this->turnoServices->searchTurnoSNT(
            $params,
            function ($response) {
                return $response;
            },
            function ($error) {
                return $this->respuestaError($error);
            }
        );
    }

    /**
     * Obtener un turno por ID
     *
     * @param $id
     * @return mixed
     * @Get("/snt/turnos/{id}")
     */
    public function getTurnoSNTAction($id)
    {
        $this->turnoServices = $this->getTurnoServices();
        return $this->turnoServices->getItemTurnoSNT($id);
    }

    /**
     * Listado de turnos recepcionados por ventanilla y punto de atención
     * @param Request $request
     * @return mixed
     * @Get("/turnos")
     */
    public function getListTurnosRecepcionadosAction(Request $request)
    {
        $params = $request->query->all();
        $this->turnoServices = $this->getTurnoServices();

        return $this->turnoServices->findAllPaginate(
            $params,
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * @param Request $request
     * @return mixed
     * @Get("/turnos/proximo")
     */
    public function nextAction(Request $request)
    {
        $params = $request->query->all();
        $this->turnoServices = $this->getTurnoServices();

        return $this->turnoServices->getProximoTurno(
            $params,
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}
