<?php
namespace ApiV1Bundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ApiV1Bundle\ApplicationServices\SecurityServices;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use ApiV1Bundle\Entity\Response\RespuestaConEstado;

class HeadersListener
{
    private $securityServices;
    private $routes = [];

    public function __construct(SecurityServices $securityServices, Array $routes)
    {
        $this->securityServices = $securityServices;
        $this->routes = $routes;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        // validate options response
        $optionsResponse = $this->optionsResponse($request);
        if ($optionsResponse) {
            $event->setResponse($optionsResponse);
        }
        // validate token
        $tokenResponse = $this->tokenValidationResponse(
            $request->getPathInfo(),
            $request->headers->get('token', null)
        );
        if ($tokenResponse) {
            $event->setResponse($tokenResponse);
        }
    }

    /**
     * Si es un OPTIONS devolver una respuesta estandar
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|NULL
     */
    private function optionsResponse(Request $request)
    {
        if ($request->getMethod() == 'OPTIONS') {
            $response = new Response();
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With');
            return $response;
        }
        return null;
    }

    /**
     * Validamos el token
     *
     * @param Request $request
     * @throws AccessDeniedHttpException
     */
    private function tokenValidationResponse($pathInfo, $token)
    {
        if (array_key_exists($pathInfo, $this->routes)) {
            $roles = $this->routes[$pathInfo];
            $token = $this->securityServices->validarToken($token);
            if (! $token->isValid() || ! in_array($token->getRol(), $roles)) {
               return new RespuestaConEstado(
                   RespuestaConEstado::STATUS_FORBIDDEN,
                   RespuestaConEstado::CODE_FORBIDDEN,
                   'Forbidden'
               );
            }
        }
        return null;
    }
}