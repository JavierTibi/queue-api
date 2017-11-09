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
     * Obtiene un usuario
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

        /*
         *            $this->getContainerRedis(),
        $this->redis = $this->getContainerRedis();
        $val = $this->redis->sadd('cola:1', 10);
        $fecha = new \DateTime();
        $val = $this->redis->zadd('prioridad:0:cola:1', $fecha->getTimestamp(), 'hola fausto');
        dump($this->redis->zscan('prioridad:0:cola:1', 0));
        dump($val);
        exit; */
    }
}
