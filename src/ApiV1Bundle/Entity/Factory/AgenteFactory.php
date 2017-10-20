<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 19/10/2017
 * Time: 2:54 PM
 */

namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Agente;
use ApiV1Bundle\Entity\User;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;

class AgenteFactory
{
    private $agenteValidator;
    private $userValidator;

    /**
     * AgenteFactory constructor.
     * @param AgenteValidator $agenteValidator
     * @param UserValidator $userValidator
     */
    public function __construct(AgenteValidator $agenteValidator, UserValidator $userValidator)
    {
        $this->agenteValidator = $agenteValidator;
        $this->userValidator = $userValidator;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {

        $validateResultadoUser = $this->userValidator->validarParams($params);
        $validateResultadoAgente = $this->agenteValidator->validarParams($params);

        if (! $validateResultadoUser->hasError() && ! $validateResultadoAgente->hasError()) {

            $user = new User(
                $params['username'],
                $params['password']
            );

            $agente = new Agente(
                $params['nombre'],
                $params['apellido'],
                $params['puntoAtencion'],
                $params['ventanillas'],
                $user
            );

            $validateResultadoAgente->setEntity($agente);

            return $validateResultadoAgente;

        } else {

            if ($validateResultadoUser->hasError()) {
                return $validateResultadoUser;
            }
            return $validateResultadoAgente;
        }
    }
}