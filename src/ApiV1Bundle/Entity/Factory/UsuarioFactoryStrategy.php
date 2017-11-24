<?php
namespace ApiV1Bundle\Entity\Factory;

use ApiV1Bundle\Entity\Interfaces\UsuarioFactoryInterface;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Repository\VentanillaRepository;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\AdminRepository;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\ResponsableRepository;

class UsuarioFactoryStrategy implements UsuarioFactoryInterface
{
    private $userValidator;
    private $ventanillaRepository;
    private $puntoAtencionRepository;
    private $adminRepository;
    private $agenteRepository;
    private $responsableRepository;
    private $factory;
    private $repository;

    public function __construct(
        UserValidator $userValidator,
        VentanillaRepository $ventanillaRepository,
        PuntoAtencionRepository $puntoAtencionRepository,
        AdminRepository $adminRepository,
        AgenteRepository $agenteRepository,
        ResponsableRepository $responsableRepository,
        $userType
    ) {
        $this->userValidator = $userValidator;
        $this->ventanillaRepository = $ventanillaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->adminRepository = $adminRepository;
        $this->agenteRepository = $agenteRepository;
        $this->responsableRepository = $responsableRepository;
        $this->factory = $this->setFactory($userType);
    }

    /**
     * Crear nuevo usuario
     *
     * {@inheritDoc}
     * @see \ApiV1Bundle\Entity\Interfaces\UsuarioInterface::create()
     */
    public function create($params)
    {
        return $this->factory->create($params);
    }

    /**
     * Obtener el repositorio
     *
     * @return \ApiV1Bundle\Repository\ResponsableRepository|\ApiV1Bundle\Repository\AdminRepository|\ApiV1Bundle\Repository\AgenteRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Encriptamos el password del usuario para poder guardarlo en la base de datos
     *
     * @param $user
     * @param $encoder
     */
    public function securityPassword($user, $encoder)
    {
        $this->factory->securityPassword($user, $encoder);
    }

    /**
     * Seteamos el factory de acuerdo al tipo de usuario
     *
     * @param $userType
     * @return factory
     */
    private function setFactory($userType)
    {
        switch ($userType) {
            case User::ROL_ADMIN:
                return $this->adminFactorySetup();
                break;
            case User::ROL_AGENTE:
                return $this->agenteFactorySetup();
                break;
            case User::ROL_RESPONSABLE:
                return $this->responsableFactorySetup();
                break;
        }
    }

    /**
     * Factory de los usuarios tipo admin
     *
     * @return \ApiV1Bundle\Entity\Factory\AdminFactory
     */
    private function adminFactorySetup()
    {
        $this->repository = $this->adminRepository;
        $factory = new AdminFactory($this->userValidator);
        return $factory;
    }

    /**
     * Factory de los usuarios tipo agente
     *
     * @return \ApiV1Bundle\Entity\Factory\AgenteFactory
     */
    private function agenteFactorySetup()
    {
        $this->repository = $this->agenteRepository;
        $factory = new AgenteFactory(
            $this->userValidator,
            $this->ventanillaRepository,
            $this->puntoAtencionRepository
        );
        return $factory;
    }

    /**
     * Factory de los usuarios tipo responsable
     *
     * @return \ApiV1Bundle\Entity\Factory\ResponsableFactory
     */
    private function responsableFactorySetup()
    {
        $this->repository = $this->responsableRepository;
        $factory = new ResponsableFactory($this->userValidator, $this->puntoAtencionRepository);
        return $factory;
    }
}
