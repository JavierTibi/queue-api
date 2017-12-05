<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 31/10/2017
 * Time: 5:25 PM
 */

namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Factory\UsuarioFactoryStrategy;
use ApiV1Bundle\Entity\Sync\UsuarioSyncStrategy;
use ApiV1Bundle\Entity\UsuarioStrategy;
use ApiV1Bundle\Entity\Validator\AdminValidator;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\ResponsableValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\ExternalServices\NotificationsExternalService;
use ApiV1Bundle\Repository\AdminRepository;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\ResponsableRepository;
use ApiV1Bundle\Repository\UserRepository;
use ApiV1Bundle\Repository\UsuarioRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use ApiV1Bundle\Helper\ServicesHelper;

class UsuarioServices extends SNCServices
{
    private $encoder;
    private $userValidator;
    private $adminValidator;
    private $responsableValidator;
    private $agenteRepository;
    private $responsableRepository;
    private $adminRepository;
    private $userRepository;
    private $ventanillaRepository;
    private $agenteValidator;
    private $puntoAtencionRepository;
    private $usuarioRepository;
    private $notificationsService;


    public function __construct(
        Container $container,
        UserPasswordEncoder $encoder,
        UserValidator $userValidator,
        AdminValidator $adminValidator,
        ResponsableValidator $responsableValidator,
        AgenteValidator $agenteValidator,
        AgenteRepository $agenteRepository,
        AdminRepository $adminRepository,
        ResponsableRepository $responsableRepository,
        UserRepository $userRepository,
        VentanillaRepository $ventanillaRepository,
        PuntoAtencionRepository $puntoAtencionRepository,
        UsuarioRepository $usuarioRepository,
        NotificationsExternalService $notificationsService
    ) {
        parent::__construct($container);
        $this->encoder = $encoder;
        $this->userValidator = $userValidator;
        $this->adminValidator = $adminValidator;
        $this->responsableValidator = $responsableValidator;
        $this->agenteValidator = $agenteValidator;
        $this->agenteRepository = $agenteRepository;
        $this->adminRepository = $adminRepository;
        $this->responsableRepository = $responsableRepository;
        $this->userRepository = $userRepository;
        $this->ventanillaRepository = $ventanillaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->notificationsService = $notificationsService;
    }

    /**
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        $validateResult = $this->userValidator->validarCreate($params);
        $repository = null;
        $userdata = null;

        if (! $validateResult->hasError()) {
            $usuarioFactory = new UsuarioFactoryStrategy(
                $this->userValidator,
                $this->ventanillaRepository,
                $this->puntoAtencionRepository,
                $this->adminRepository,
                $this->agenteRepository,
                $this->responsableRepository,
                $params['rol']
            );
            $repository = $usuarioFactory->getRepository();
            $validateResult = $usuarioFactory->create($params);

            $userData = [
                'title' => '¡Usuario creado con éxito!',
                'email' => null,
                'password' => null
            ];

            // securizar contraseña
            if (! $validateResult->hasError()) {
                $user = $validateResult->getEntity()->getUser();
                // user data
                $userData['email'] = $user->getUsername();
                $userData['password'] = $user->getPassword();
                // make the password secure
                $usuarioFactory->securityPassword($user, $this->getSecurityPassword());
            }
        }

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess, $repository, $userData) {
                return call_user_func_array($sucess, [$repository->save($entity), $userData]);
            },
            $error
        );
    }

    /**
     * Listado de todos los usuarios
     *
     * @param $limit
     * @param $offset
     * @return object
     */
    public function findAllPaginate($limit, $offset)
    {
        $usuarios = $this->usuarioRepository->findAllPaginate($offset, $limit);
        $result = [];
        foreach ($usuarios as $usuario) {
            $result[] = [
                'id' => $usuario->getUser()->getId(),
                'nombre' => $usuario->getNombre(),
                'apellido' => $usuario->getApellido(),
                'rol' => $usuario->getUser()->getRol(),
                'puntoAtencion' => [
                    'id' => $usuario->getPuntoAtencionId(),
                    'nombre' => $usuario->getNombrePuntoAtencion()
                ]
            ];
        }
        $resultset = [
            'resultset' => [
                'count' => $this->usuarioRepository->getTotal(),
                'offset' => $offset,
                'limit' => $limit
            ]
        ];

        return $this->respuestaData($resultset, $result);
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        $result =[];
        $user = $this->userRepository->find($id);
        $validateResultado = $this->userValidator->validarUser($user);

        if (! $validateResultado->hasError()) {
            $usuarioRepository = new UsuarioStrategy(
                $this->agenteRepository,
                $this->responsableRepository,
                $this->adminRepository
            );
            $result = $usuarioRepository->getUser($user);
            return $this->respuestaData([], $result);
        }

        return $this->respuestaData([], $validateResultado->getErrors());
    }


    /**
     * Editar un usuario
     *
     * @param type $params Array con los datos a modificar
     * @param type $idUser ID del usuario a modificar
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function edit($params, $idUser, $sucess, $error)
    {
        $user = $this->userRepository->find($idUser);
        $validateResult = $this->userValidator->validarUsuario($user);
        $repository = null;
        if (! $validateResult->hasError()) {
            $userSync = new UsuarioSyncStrategy(
                $this->userValidator,
                $this->adminRepository,
                $this->adminValidator,
                $this->agenteRepository,
                $this->agenteValidator,
                $this->responsableRepository,
                $this->responsableValidator,
                $this->ventanillaRepository,
                $this->puntoAtencionRepository,
                $user->getRol()
            );
            $repository = $userSync->getRepository();
            $validateResult = $userSync->edit($idUser, $params);
        }

        return $this->processResult(
            $validateResult,
            function () use ($sucess, $repository) {
                return call_user_func($sucess, $repository->flush());
            },
            $error
        );
    }

    /**
     * Elimina un usuario
     *
     * @param integer $id Identificador único del área
     * @param $success | Indica si tuvo éxito o no
     * @param string $error Mensaje con el error ocurrido al borrar un área
     * @return mixed
     */
    public function delete($id, $success, $error)
    {
        $user = $this->userRepository->find($id);
        $validateResult = $this->userValidator->validarUsuario($user);
        $repository = null;
        if (! $validateResult->hasError()) {
            $userSync = new UsuarioSyncStrategy(
                $this->userValidator,
                $this->adminRepository,
                $this->adminValidator,
                $this->agenteRepository,
                $this->agenteValidator,
                $this->responsableRepository,
                $this->responsableValidator,
                $this->ventanillaRepository,
                $this->puntoAtencionRepository,
                $user->getRol()
            );
            $repository = $userSync->getRepository();
            $validateResult = $userSync->delete($id);
        }

        return $this->processResult(
            $validateResult,
            function ($entity) use ($success, $repository) {
                return call_user_func($success, $repository->remove($entity));
            },
            $error
        );
    }

    public function modificarPassword($params, $onSuccess, $onError)
    {
        $username = isset($params['username']) ? $params['username'] : null;
        $user = $this->userRepository->findOneByUsername($username);
        $validateResult = $this->userValidator->validarUsuario($user);

        $userData = [
            'title' => '¡Contraseña modificada con éxito!',
            'email' => null,
            'password' => null
        ];

        $repository = $this->userRepository;

        if (! $validateResult->hasError()) {            
            // user data
            $userData['email'] = $user->getUsername();
            $userData['password'] = ServicesHelper::randomPassword(12);
            // make the password secure
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));
        }

        return $this->processResult(
            $validateResult,
            function ($entity) use ($onSuccess, $repository, $userData) {
                return call_user_func_array($onSuccess, [$repository->flush(), $userData]);
            },
            $onError
        );
    }

    /**
     * Enviar mail al usuario
     *
     * @param $userdata
     * @return mixed|array|NULL|string
     */
    public function enviarEmailUsuario($userData, $template)
    {
        $response = $this->notificationsService->enviarNotificacion(
            $this->notificationsService->getEmailTemplate($template),
            $userData['email'],
            '20359715286',
            $userData
        );
        return $response;
    }
}
