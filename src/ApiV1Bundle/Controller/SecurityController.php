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
     * User logout
     *
     * @param Request $request
     * @Post("/auth/logout")
     */
    public function logout(Request $request)
    {
        $token = $request->headers->get('authorization', null);
        $this->securityServices = $this->getSecurityServices();
        return $this->securityServices->logout(
            $token,
            function ($token) {
                return $this->respuestaOk('Sesion terminada');
            },
            function ($error) {
                return $this->respuestaError($error);
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
