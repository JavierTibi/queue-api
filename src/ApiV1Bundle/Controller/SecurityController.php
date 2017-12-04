<?php
namespace ApiV1Bundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends ApiController
{

    private $securityServices;
    private $usuarioServices;

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
     * Modificar contraseña del usuario
     *
     * @param Request $request
     * @Post("auth/reset")
     */
    public function modificarPassword(Request $request)
    {
        $params = $request->request->all();
        $this->usuarioServices = $this->getUsuarioServices();
        return $this->usuarioServices->modificarPassword(
            $params,
            function ($result, $userData) {
                return $this->respuestaOk(
                    'Contraseña modificada con éxito',
                    $this->usuarioServices->enviarEmailUsuario($userData, 'usuario')
                );
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    /**
     * GET Test token
     *
     * @param Request $request
     * @Get("/auth/test")
     */
    public function validateGetSimplePath(Request $request)
    {
        return [
            'Let me know if you can see this!'
        ];
    }

    /**
     * POST Test token
     *
     * @param Request $request
     * @Post("/auth/test")
     */
    public function validatePostSimplePath(Request $request)
    {
        return [
            'Let me know if you can see this!'
        ];
    }

    /**
     * POST Test token
     *
     * @param Request $request
     * @Post("/auth/test/{something}")
     */
    public function validateComplexPath(Request $request, $something)
    {
        return [
            'Let me know if you can see this!',
            $something
        ];
    }
}
