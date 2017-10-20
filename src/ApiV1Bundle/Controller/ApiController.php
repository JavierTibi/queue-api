<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

/**
 * Class ApiController
 *
 * Clase base de los controladores de la API
 * @author Fausto Carrera <fcarrera@hexacta.com>
 *
 * @package ApiV1Bundle\Controller
 */
class ApiController extends FOSRestController
{

    /**
     * Obtiene Login service
     *
     * @return object
     */
    protected function getLoginServices()
    {
        return $this->container->get('snc.services.login');
    }
}
