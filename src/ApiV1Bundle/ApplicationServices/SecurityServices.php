<?php
namespace ApiV1Bundle\ApplicationServices;

use Symfony\Component\DependencyInjection\Container;
use ApiV1Bundle\Repository\UserRepository;
use ApiV1Bundle\Helper\JWToken;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Repository\TokenRepository;
use ApiV1Bundle\Entity\Factory\TokenFactory;

class SecurityServices extends SNCServices
{
    private $userRepository;
    private $tokenRepository;
    private $jwtoken;
    private $userValidator;

    public function __construct(
        Container $container,
        UserRepository $userRepository,
        TokenRepository $tokenRepository,
        JWToken $jwtoken,
        UserValidator $userValidator
    ) {
        parent::__construct($container);
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->jwtoken = $jwtoken;
        $this->userValidator = $userValidator;
    }

    /**
     * User login
     */
    public function login($params, $onError)
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
            $onError
        );
    }

    /**
     * User logout
     * @param string $token
     * @param function $onError
     */
    public function logout($authorization, $success, $error)
    {
        // validamos el token
        if ($this->validarToken($authorization)) {
            $token = md5($authorization);
            // agregamos el token a la lista si no existe
            $verificarCancelado = $this->tokenRepository->findOneByToken($token);
            if (! $verificarCancelado) {
                $tokenFactory = new TokenFactory($this->tokenRepository);
                $validateResult = $tokenFactory->insert($token);
                return $this->processResult(
                    $validateResult,
                    function ($entity) use ($success) {
                        return call_user_func($success, $this->tokenRepository->save($entity));
                    },
                    $error
                );
            }
        }
        return call_user_func($success, []);
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
        $token = md5($authorization);
        $tokenCancelado = $this->tokenRepository->findOneByToken($token);
        list($bearer, $token) = explode(' ', $authorization);
        return $this->jwtoken->validate($token, $tokenCancelado);
    }
}
