<?php
namespace ApiV1Bundle\ApplicationServices;

use Symfony\Component\DependencyInjection\Container;
use ApiV1Bundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use ApiV1Bundle\Helper\JWToken;
use ApiV1Bundle\Entity\Validator\UserValidator;

class SecurityServices extends SNCServices
{
    private $userRepository;
    private $jwtoken;
    private $userValidator;

    public function __construct(
        Container $container,
        UserRepository $userRepository,
        JWToken $jwtoken,
        UserValidator $userValidator
    ) {
        parent::__construct($container);
        $this->userRepository = $userRepository;
        $this->jwtoken = $jwtoken;
        $this->userValidator = $userValidator;
    }

    /**
     * User login
     */
    public function login($params, $error)
    {
        $username = isset($params['username']) ? $params['username'] : null;
        $user = $this->userRepository->findOneByUsername($username);
        $validateResult = $this->userValidator->validarParamsLogin($params, $user);
        if (! $validateResult->hasError()) {
            $validateResult = $this->userValidator->validarLogin($user, $params['password']);
            if (! $validateResult->hasError()) {
                return [
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'token' => $this->jwtoken->getToken($user->getId(), $user->getUsername(), $user->getRoles()),
                    'rol' => $user->getRoles()
                ];
            }
        }
        return $this->processResult(
            $validateResult,
            function ($result) {
                return $result;
            },
            $error
        );
    }

    /**
     * Validar token
     *
     * @param $username
     * @param $token
     * @return array
     */
    public function validarToken($authorization)
    {
        list($bearer, $token) = explode(' ', $authorization);
        return $this->jwtoken->validate($token);
    }
}
