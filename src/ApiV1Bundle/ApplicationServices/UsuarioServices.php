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
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Repository\AdminRepository;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\ResponsableRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

class UsuarioServices extends SNCServices
{
    private $userValidator;
    private $agenteRepository;
    private $responsableRepository;
    private $adminRepository;
    private $ventanillaRepository;


    public function __construct(
        Container $container,
        UserValidator $userValidator,
        AgenteRepository $agenteRepository,
        AdminRepository $adminRepository,
        ResponsableRepository $responsableRepository,
        VentanillaRepository $ventanillaRepository
    )
    {
        parent::__construct($container);
        $this->userValidator = $userValidator;
        $this->agenteRepository = $agenteRepository;
        $this->adminRepository = $adminRepository;
        $this->responsableRepository = $responsableRepository;
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
}