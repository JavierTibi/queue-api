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


class AgenteController extends Controller
{

    /**
     * Crear un agente
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/agentes")
     */
    public function postAction(Request $request)
    {
     /*   $params = $request->request->all();
        $agente = new Agente($params['nombre'],$params['apellido'], $params['username'], $params['password']);


        $em = $this->getDoctrine()->getManager();
        $em->persist($agente);
        $em->flush();

        return new Response('Created product id '.$agente->getId());*/
    }
}