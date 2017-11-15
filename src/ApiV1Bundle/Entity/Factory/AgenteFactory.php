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
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\VentanillaRepository;

class AgenteFactory
{
    private $userValidator;
    private $ventanillaRepository;
    private $puntoAtencionRepository;


    /**
     * AgenteFactory constructor.
     * @param UserValidator $userValidator
     * @param VentanillaRepository $ventanillaRepository
     */
    public function __construct(
        UserValidator $userValidator,
        VentanillaRepository $ventanillaRepository,
        PuntoAtencionRepository $puntoAtencionRepository)
    {
        $this->userValidator = $userValidator;
        $this->ventanillaRepository = $ventanillaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoAtencion']);
        $validateResultado = $this->userValidator->validarParamsAgente($params, $puntoAtencion);

        if (! $validateResultado->hasError()) {

            $user = new User(
                $params['username'],
                $params['rol']
            );

            $agente = new Agente(
                $params['nombre'],
                $params['apellido'],
                $puntoAtencion,
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