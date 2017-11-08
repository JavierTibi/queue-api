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
    
    /**
     * Editar un usuario
     * 
     * @param Request $request Espera el resultado de una petición como parámetro
     * @param integer $idUser Espera el id del usuario
     * @return mixed
     * @Put("/usuarios/{idUser}")
     */
    public function putAction(Request $request, $idUser) {
        $params = $request->request->all();
        $this->usuarioServices = $this->getUsuarioServices();
        
        return $this->usuarioServices->edit(
            $params,
            $idUser,
            function () {
                return $this->respuestaOk('Usuario modificado con éxito');
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
     * @Delete("/usuarios/{id}")
     */
    public function deleteAction($id)
    {
        $this->usuarioServices = $this->getUsuarioServices();
        return $this->usuarioServices->delete(
            $id,
            function () {
                return $this->respuestaOk('Usuario eliminado con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}