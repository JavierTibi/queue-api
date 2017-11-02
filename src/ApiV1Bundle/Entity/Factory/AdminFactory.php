<?php

namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Admin;
use ApiV1Bundle\Entity\Responsable;
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;

class AdminFactory
{
    private $userValidator;

    /**
     * ResponsableFactory constructor.
     * @param UserValidator $userValidator
     */
    public function __construct(
        UserValidator $userValidator)
    {
        $this->userValidator = $userValidator;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {

        $validateResultado = $this->userValidator->validarParamsAdmin($params);

        if (! $validateResultado->hasError()) {

            $user = new User(
                $params['username'],
                $params['rol']
            );

            $admin = new Admin(
                $params['nombre'],
                $params['apellido'],
                $user
            );

            $validateResultado->setEntity($admin);
        }

        return $validateResultado;
    }
}