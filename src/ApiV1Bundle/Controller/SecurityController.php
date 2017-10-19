<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends ApiController
{

    /**
     *
     * @param Request $request
     * @Post("/login")
     */
    public function login(Request $request)
    {
        $username = $request->get('username', null);
        $password = $request->get('password', null);
        $this->loginServices = $this->getLoginServices();
        return $this->loginServices->login($username, $password);
    }
}
