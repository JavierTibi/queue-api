<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 31/10/2017
 * Time: 5:25 PM
 */

namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Factory\AdminFactory;
use ApiV1Bundle\Entity\Factory\AgenteFactory;
use ApiV1Bundle\Entity\Factory\ResponsableFactory;
use ApiV1Bundle\Entity\Sync\AdminSync;
use ApiV1Bundle\Entity\Sync\AgenteSync;
use ApiV1Bundle\Entity\Sync\ResponsableSync;
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\AdminValidator;
use ApiV1Bundle\Entity\Validator\ResponsableValidator;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Repository\AdminRepository;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\ResponsableRepository;
use ApiV1Bundle\Repository\UserRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

class UsuarioServices extends SNCServices
{
    private $userValidator;
    private $adminValidator;
    private $responsableValidator;
    private $agenteRepository;
    private $responsableRepository;
    private $adminRepository;
    private $userRepository;
    private $ventanillaRepository;
    private $agenteValidator;


    public function __construct(
        Container $container,
        UserValidator $userValidator,
        ResponsableValidator $responsableValidator,
        AgenteValidator $agenteValidator,
        AgenteRepository $agenteRepository,
        AdminRepository $adminRepository,
        ResponsableRepository $responsableRepository,
        UserRepository $userRepository,
        VentanillaRepository $ventanillaRepository
            
    )
    {
        parent::__construct($container);
        $this->userValidator = $userValidator;
        $this->responsableValidator = $responsableValidator;
        $this->agenteValidator = $agenteValidator;
        $this->agenteRepository = $agenteRepository;
        $this->adminRepository = $adminRepository;
        $this->responsableRepository = $responsableRepository;
        $this->userRepository = $userRepository;
        $this->ventanillaRepository = $ventanillaRepository;
    }

    /**
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        if ($params['rol'] == User::ROL_AGENTE) {
            $usuarioFactory = new AgenteFactory(
                $this->userValidator,
                $this->ventanillaRepository
            );

            $repository = $this->agenteRepository;

        } elseif ($params['rol'] == User::ROL_RESPONSABLE) {
            $usuarioFactory = new ResponsableFactory(
                $this->userValidator
            );
            $repository = $this->responsableRepository;

        } else {
            $usuarioFactory = new AdminFactory(
                $this->userValidator
            );
            $repository = $this->adminRepository;
        }

        $validateResult = $usuarioFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess, $repository) {
                return call_user_func($sucess, $repository->save($entity));
            },
            $error
        );
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
    public function edit($params, $idUser, $sucess, $error) {
        
        $user = $this->userRepository->find($idUser);
        $validateResult = $this->userValidator->validarUsuario($user);

        if ( ! $validateResult->hasError() ) {
            switch ($user->getRol()){
                case User::ROL_ADMIN:
                    $userSync = new AdminSync(
                        $this->userValidator,
                        $this->adminRepository
                    );
                    $repository = $this->adminRepository;
                    break;
                case User::ROL_RESPONSABLE:
                    $userSync = new ResponsableSync(
                        $this->userValidator,
                        $this->responsableRepository,
                        $this->responsableValidator
                    );
                    $repository = $this->responsableRepository;
                    break;
                case User::ROL_AGENTE:
                    $userSync = new AgenteSync(
                        $this->agenteValidator,
                        $this->agenteRepository,
                        $this->ventanillaRepository
                    );
                    $repository = $this->agenteRepository;
                    break;
            }

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
        if ( ! $validateResult->hasError() ) {
            switch ($user->getRol()){
                case User::ROL_ADMIN:
                    $userSync = new AdminSync(
                        $this->userValidator,
                        $this->adminRepository
                    );
                    $repository = $this->adminRepository;
                    break;
                case User::ROL_RESPONSABLE:
                    $userSync = new ResponsableSync(
                        $this->userValidator,
                        $this->responsableRepository,
                        $this->responsableValidator
                    );
                    $repository = $this->responsableRepository;
                    break;
                case User::ROL_AGENTE:
                    $userSync = new AgenteSync(
                        $this->agenteValidator,
                        $this->agenteRepository,
                        $this->ventanillaRepository
                    );
                    $repository = $this->agenteRepository;
                    break;
            }

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

}
