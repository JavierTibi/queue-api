<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
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
        $usuarioServices = $this->getUsuarioServices();

        return $usuarioServices->create(
            $params,
            function ($usuario, $userdata) use ($usuarioServices) {
                return $this->respuestaOk('Usuario creado con éxito', [
                    'id' => $usuario->getUser()->getId(),
                    'response' => $usuarioServices->enviarEmailUsuario($userdata, 'usuario')
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
    public function putAction(Request $request, $idUser)
    {
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

    /**
     * Listado de usuarios
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Get("/usuarios")
     */
    public function getListAction(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $this->usuarioServices = $this->getUsuarioServices();
        return $this->usuarioServices->findAllPaginate((int) $limit, (int) $offset);
    }

    /**
     * Obtiene un usuario
     *
     * @param integer $id Identificador único
     * @return mixed
     * @Get("/usuarios/{id}")
     */
    public function getItemAction($id)
    {
        $this->usuarioServices = $this->getUsuarioServices();
        return $this->usuarioServices->get($id);
    }
}
