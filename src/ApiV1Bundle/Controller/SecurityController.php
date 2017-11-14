<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends ApiController
{

    /**
     * User login
     *
     * @param Request $request
     * @Post("/auth/login")
     */
    public function login(Request $request)
    {
        $params = $request->request->all();
        $this->securityServices = $this->getSecurityServices();
        return $this->securityServices->login(
            $params,
            function ($err) {
                return $this->respuestaForbiddenRequest($err);
            }
        );
    }

    /**
     * Validar token
     *
     * @param Request $request
     * @Post("/auth/test")
     */
    public function validate(Request $request)
    {
        return [
            'Let me know if you can see this!'
        ];
    }
}
