<?php

namespace ApiV1Bundle\Entity\Sync;

use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Admin;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\AdminValidator;
use ApiV1Bundle\Repository\AdminRepository;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Entity\Interfaces\UsuarioSyncInterface;

/**
 * Class AdminSync
 * @package ApiV1Bundle\Entity\Sync
 */
class AdminSync implements UsuarioSyncInterface
{
    private $userValidator;
    private $adminValidator;
    private $adminRepository;

    /**
     * AdminSync constructor
     * @param UserValidator $userValidator
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        UserValidator $userValidator,
        AdminValidator $adminValidator,
        AdminRepository $adminRepository)
    {
        $this->userValidator = $userValidator;
        $this->adminValidator = $adminValidator;
        $this->adminRepository = $adminRepository;
    }

    public function edit($id, $params)
    {
        $validateResultado = $this->adminValidator->validarParams($params);

        if (! $validateResultado->hasError()) {

            $admin = $this->adminRepository->findOneByUser($id);
            $user = $admin->getUser();

            $admin->setNombre($params['nombre']);
            $admin->setApellido($params['apellido']);

            if (isset($params['username'])) {
                $user->setUsername($params['username']);
            }

            if (isset($params['password'])) {
                $user->setPassword($params['password']);
            }

            $validateResultado->setEntity($admin);
        }

        return $validateResultado;
    }

    /**
     * Borra un admin
     * @param integer $id Identificador único del admin
     *
     * @return ValidateResultado
     */
    public function delete($id)
    {
        $admin = $this->adminRepository->findOneByUser($id);

        $validateResultado = $this->userValidator->validarUsuario($admin);

        if (! $validateResultado->hasError()) {
            $validateResultado->setEntity($admin);
            return $validateResultado;
        }
        return $validateResultado;
    }
}
