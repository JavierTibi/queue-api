<?php

namespace ApiV1Bundle\Controller;

use ApiV1Bundle\Entity\Agente;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AgenteController extends ApiController
{
    private $agenteServices;

    /**
     * Crear un agente
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/agentes")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->agenteServices = $this->getAgenteServices();

        return $this->agenteServices->create(
            $params,
            function ($agente) {
                return $this->respuestaOk('Agente creado con éxito', [
                    'id' => $agente->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Modificar un agente
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Put("/agentes/{id}")
     */
    public function putAction(Request $request, $id)
    {
        $params = $request->request->all();
        $this->agenteServices = $this->getAgenteServices();

        return $this->agenteServices->edit(
            $params,
            $id,
            function () {
                return $this->respuestaOk('Agente modificado con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Eliminar un agente
     *
     * @param integer $id Identificador único del agente
     * @return mixed
     * @Delete("/agentes/{id}")
     */
    public function deleteAction($id)
    {
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->delete(
            $id,
            function () {
                return $this->respuestaOk('Agente eliminado con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * Asigna una ventanilla a un Agente
     * @param $idVentanilla
     * @Post("/agentes/{idAgente}/ventanilla/{idVentanilla}")
     */
    public function asignarVentanillaAction($idAgente, $idVentanilla)
    {
        $this->agenteServices = $this->getAgenteServices();
        return $this->agenteServices->asignarVentanilla(
            $idAgente,
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