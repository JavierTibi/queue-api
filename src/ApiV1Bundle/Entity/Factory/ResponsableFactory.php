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
use ApiV1Bundle\Repository\PuntoAtencionRepository;

class ResponsableFactory
{

    private $userValidator;
    private $puntoAtencionRepository;

    /**
     * ResponsableFactory constructor.
     * @param UserValidator $userValidator
     */
    public function __construct(
        UserValidator $userValidator,
        PuntoAtencionRepository $puntoAtencionRepository
    )
    {
        $this->userValidator = $userValidator;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoAtencion']);
        $validateResultado = $this->userValidator->validarParamsResponsable($params, $puntoAtencion);

        if (! $validateResultado->hasError()) {

            $user = new User(
                $params['username'],
                $params['rol']
            );

            $responsable = new Responsable(
                $params['nombre'],
                $params['apellido'],
                $puntoAtencion,
                $user
            );

            $validateResultado->setEntity($responsable);
        }

        return $validateResultado;
    }
}