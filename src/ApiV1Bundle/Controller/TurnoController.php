<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:01 PM
 */

namespace ApiV1Bundle\Controller;


use ApiV1Bundle\Entity\Turno;
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
}