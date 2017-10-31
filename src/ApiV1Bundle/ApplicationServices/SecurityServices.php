<?php
namespace ApiV1Bundle\ApplicationServices;

use Symfony\Component\DependencyInjection\Container;
use ApiV1Bundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use ApiV1Bundle\Helper\JWToken;

class SecurityServices extends SNCServices
{
    private $userRepository;
    private $encoder;
    private $jwtoken;

    public function __construct(
        Container $container,
        UserRepository $userRepository,
        UserPasswordEncoder $encoder,
        JWToken $jwtoken
    ) {
        parent::__construct($container);
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->jwtoken = $jwtoken;
    }

    /**
     * User login
     */
    public function login($username, $password)
    {
        $result = [
            'user' => 'Usuario/contraseña incorrectos'
        ];
        $user = $this->userRepository->findOneByUsername($username);
        if ($user) {
            if ($this->encoder->isPasswordValid($user, $password)) {
                $result = [
                    'username' => $user->getUsername(),
                    'token' => $this->jwtoken->getToken($user->getId(), $user->getUsername(), $user->getRoles())
                ];
            }
        }
        return $this->respuestaData([], $result);
    }

    /**
     * Validar token
     *
     * @param $username
     * @param $token
     * @return array
     */
    public function validarToken($token)
    {
        return $this->jwtoken->validate($token);
    }
}
