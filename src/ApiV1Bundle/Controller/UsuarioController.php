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

class UsuarioController extends ApiController
{

    private $usuarioServices;

    /**
     * Crear un usuario
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/usuarios")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->usuarioServices = $this->getUsuarioServices();

        return $this->usuarioServices->create(
            $params,
            function ($usuario) {
                return $this->respuestaOk('Usuario creado con éxito', [
                    'id' => $usuario->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}