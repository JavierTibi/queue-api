<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

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

    /**
     * Crea punto de atencion
     * 
     * @param Request $request Se envian los datos para crear el punto de atencion
     * @return Respuesta con estado
     * @Post("/puntosatencion")
     */
    public function postAction(Request $request) {
        $params = $request->request->all();
        $puntoAtencionServices = $this->getPuntoAtencionServices();
        return $puntoAtencionServices->create(
                $params,
                function ($puntoAtencion) {
                return $this->respuestaOk('Punto Atencion creado con éxito', [
                    'id' => $puntoAtencion->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
    
    /**
     * Editar punto de atencion
     * 
     * @param Request $request
     * @param type $id
     * @return type
     * @Put("/puntosatencion/{id}")
     */
    public function putAction(Request $request, $id) {
        $params = $request->request->all();
        $puntoAtencionServices = $this->getPuntoAtencionServices();
        return $puntoAtencionServices->edit(
                $id,
                $params,
                function ($puntoAtencion){
                    return $this->respuestaOk('Punto de Atencion editado con éxito', []);
                },
                function($err){
                    return $this->respuestaError($err);
                }
            );
    }
    
    /**
     * Eliminar Punto de Atencion
     * 
     * @param Request $request
     * @return type
     * @Delete("/puntosatencion/{id}")
     */
    public function deleteAction($id) {
        $puntoAtencionServices = $this->getPuntoAtencionServices();
        return $puntoAtencionServices->delete(
            $id,
            function ($puntoAtencion) {
                return $this->respuestaOk('Punto de atención eliminado con éxito');
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }
}
