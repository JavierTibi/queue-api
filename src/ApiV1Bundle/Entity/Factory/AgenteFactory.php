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
use ApiV1Bundle\Entity\Ventanilla;
use ApiV1Bundle\Repository\VentanillaRepository;

class AgenteFactory
{
    private $userValidator;
    private $ventanillaRepository;


    /**
     * AgenteFactory constructor.
     * @param UserValidator $userValidator
     * @param VentanillaRepository $ventanillaRepository
     */
    public function __construct(
        UserValidator $userValidator,
        VentanillaRepository $ventanillaRepository)
    {
        $this->userValidator = $userValidator;
        $this->ventanillaRepository = $ventanillaRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {

        $validateResultado = $this->userValidator->validarParamsAgente($params);

        if (! $validateResultado->hasError()) {

            $user = new User(
                $params['username'],
                $params['rol']
            );

            $agente = new Agente(
                $params['nombre'],
                $params['apellido'],
                $params['puntoAtencion'],
                $params['ventanillas'],
                $user
            );

            foreach ($params['ventanillas'] as $idVentanilla) {
                $ventanilla = $this->ventanillaRepository->find($idVentanilla);
                $agente->addVentanilla($ventanilla);
            }

            $validateResultado->setEntity($agente);
        }

        return $validateResultado;
    }
}