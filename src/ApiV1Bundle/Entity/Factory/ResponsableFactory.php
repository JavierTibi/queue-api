<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 1/11/2017
 * Time: 11:00 AM
 */

namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Responsable;
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;

class ResponsableFactory
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

        $validateResultado = $this->userValidator->validarParamsResponsable($params);

        if (! $validateResultado->hasError()) {

            $user = new User(
                $params['username'],
                $params['rol']
            );

            $responsable = new Responsable(
                $params['nombre'],
                $params['apellido'],
                $params['puntoAtencion'],
                $user
            );

            $validateResultado->setEntity($responsable);
        }

        return $validateResultado;
    }
}