<?php

namespace ApiV1Bundle\Entity\Sync;


use ApiV1Bundle\Entity\Agente;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\VentanillaRepository;

/**
 * Class AgenteSync
 * @package ApiV1Bundle\Entity\Sync
 */
class AgenteSync
{
    private $agenteValidator;
    private $agenteRepository;
    private $ventanillaRepository;

    /**
     * AgenteSync constructor.
     * @param AgenteValidator $agenteValidator
     * @param AgenteRepository $agenteRepository
     * @param VentanillaRepository $ventanillaRepository
     */
    public function __construct(
        AgenteValidator $agenteValidator,
        AgenteRepository $agenteRepository,
        VentanillaRepository $ventanillaRepository)
    {
        $this->agenteValidator = $agenteValidator;
        $this->agenteRepository = $agenteRepository;
        $this->ventanillaRepository = $ventanillaRepository;
    }

    public function edit($id, $params)
    {
        $validateResultado = $this->agenteValidator->validarParams($params);

        if (! $validateResultado->hasError()) {

            $agente = $this->agenteRepository->find($id);
            $user = $agente->getUser();

            $agente->setNombre($params['nombre']);
            $agente->setApellido($params['apellido']);
            //TODO find punto de atencion
            $agente->setPuntoAtencion($params['puntoAtencion']);

            $agente->removeAllVentanilla();

            foreach ($params['ventanillas'] as $idVentanilla) {
                $ventanilla = $this->ventanillaRepository->find($idVentanilla);
                $agente->addVentanilla($ventanilla);
            }

            if (isset($params['username'])) {
                $user->setUsername($params['username']);
            }

            if (isset($params['password'])) {
                $user->setPassword($params['password']);
            }

            $validateResultado->setEntity($agente);
        }

        return $validateResultado;
    }
}