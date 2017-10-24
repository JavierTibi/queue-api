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

class VentanillaController extends ApiController
{

    private $ventanillaServices;

    /**
     * Crear una ventanilla
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/ventanillas")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->ventanillaServices = $this->getVentanillaServices();

        return $this->ventanillaServices->create(
            $params,
            function ($ventanilla) {
                return $this->respuestaOk('Ventanilla creada con éxito', [
                    'id' => $ventanilla->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

}