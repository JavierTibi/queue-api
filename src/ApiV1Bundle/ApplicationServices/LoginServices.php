<?php
namespace ApiV1Bundle\ApplicationServices;

use Symfony\Component\DependencyInjection\Container;
use ApiV1Bundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class LoginServices extends SNCServices
{
    private $userRepository;
    private $encoder;

    public function __construct(Container $container, UserRepository $userRepository, UserPasswordEncoder $encoder)
    {
        parent::__construct($container);
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * User login
     */
    public function login($username, $password)
    {
        $response = [
            'user' => 'Usuario/contraseña incorrectos'
        ];
        $user = $this->userRepository->findOneByUsername($username);
        if ($user) {
            // validamos la contraseña
            if ($this->encoder->isPasswordValid($user, $password)) {
                $response = [
                    'user' => $user->getNombre() . ' ' . $user->getApellido(),
                    'token' => null
                ];
            }
        }
        return new JsonResponse($response);
    }
}
