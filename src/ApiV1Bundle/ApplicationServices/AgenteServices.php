<?php

namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Sync\AgenteSync;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Repository\AgenteRepository;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\UsuarioRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class AgenteServices
 * @package ApiV1Bundle\ApplicationServices
 */
class AgenteServices extends SNCServices
{
    private $agenteRepository;
    private $agenteValidator;
    private $usuarioRepository;
    private $userValidator;
    private $ventanillaRepository;
    private $puntoAtencionRepository;

    /**
     * AgenteServices constructor.
     * @param Container $container
     * @param AgenteRepository $agenteRepository
     * @param AgenteValidator $agenteValidator
     * @param UsuarioRepository $usuarioRepository
     * @param UserValidator $userValidator
     * @param VentanillaRepository $ventanillaRepository
     * @param PuntoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        Container $container,
        AgenteRepository $agenteRepository,
        AgenteValidator $agenteValidator,
        UsuarioRepository $usuarioRepository,
        UserValidator $userValidator,
        VentanillaRepository $ventanillaRepository,
        PuntoAtencionRepository $puntoAtencionRepository
    ) {
        parent::__construct($container);
        $this->agenteRepository = $agenteRepository;
        $this->agenteValidator = $agenteValidator;
        $this->usuarioRepository = $usuarioRepository;
        $this->userValidator = $userValidator;
        $this->ventanillaRepository = $ventanillaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }

    /**
     * @param $puntoAtencionId
     * @param $limit
     * @param $offset
     * @return object
     */
    public function findAllPaginate($puntoAtencionId, $limit, $offset)
    {
        $result = [];
        $ventanillas = [];

        $agentes = $this->agenteRepository->findAllPaginate($puntoAtencionId, $offset, $limit);

        foreach ($agentes as $item) {
            $agente = $this->agenteRepository->find($item['agente_id']);

            if (count($agente->getVentanillas())) {
                foreach ($agente->getVentanillas() as $ventanilla) {
                    $ventanillas['ventanillas'][] = $ventanilla->getIdentificador();
                }
            } else {
                $ventanillas['ventanillas'] = [];
            }

            // @Todo este unset está muy mal, algún día en el futuro hay que arreglarlo
            unset($item['agente_id']);
            $result[] = array_merge($item, $ventanillas);
            $ventanillas = [];
        }

        $resultset = [
            'resultset' => [
                'count' => $this->agenteRepository->getTotal($puntoAtencionId),
                'offset' => $offset,
                'limit' => $limit
            ]
        ];

        return $this->respuestaData($resultset, $result);
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        $agente = $this->agenteRepository->find($id);
        $validateResultado = $this->agenteValidator->validarAgente($agente);

        if (! $validateResultado->hasError()) {
            return $this->respuestaData([], $agente);
        }

        return $this->respuestaData([], null);
    }

    /**
     * @param $idUsuario
     * @param $idVentanilla
     * @param $success
     * @param $error
     * @return mixed
     */
    public function asignarVentanilla($idUsuario, $idVentanilla, $success, $error)
    {
        $agenteSync = new AgenteSync(
            $this->agenteValidator,
            $this->agenteRepository,
            $this->ventanillaRepository,
            $this->puntoAtencionRepository
        );
        $validateResult = $agenteSync->asignarVentanilla($idUsuario, $idVentanilla);

        return $this->processResult(
            $validateResult,
            function () use ($success) {
                return call_user_func($success, $this->agenteRepository->flush());
            },
            $error
        );
    }

    /**
     * Desasigna una ventanilla a un Agente
     * @param $usuario
     * @return mixed
     */
    public function desasignarVentanilla($usuario)
    {
        $agenteSync = new AgenteSync(
            $this->agenteValidator,
            $this->agenteRepository,
            $this->ventanillaRepository,
            $this->puntoAtencionRepository
        );

        $validateResult = $agenteSync->desasignarVentanilla($usuario);

        if (! $validateResult->hasError()) {
            $this->agenteRepository->flush();
        }

        return $validateResult;
    }

    /**
     * Listado de ventanillas disponibles para el agente
     * @param $id
     * @return object|\ApiV1Bundle\Entity\Response\Respuesta
     */
    public function findVentanillasAgente($id)
    {
        $usuario = $this->usuarioRepository->findOneByUser($id);
        $agente = $this->agenteRepository->find($usuario->getId());
        $validateResultado = $this->agenteValidator->validarAgente($agente);
        if (! $validateResultado->hasError()) {
            $response = [];
            // listado de ventanillas actualmente en uso
            $listaAgentes = $this->agenteRepository->findByPuntoAtencion($agente->getPuntoAtencion()->getId());
            $ventanillasEnUso = [];
            foreach ($listaAgentes as $agentePuntoAtencion) {
                if ($agentePuntoAtencion->getId() != $agente->getId()) {
                    $ventanillaActual = $agentePuntoAtencion->getventanillaActual();
                    if ($ventanillaActual) {
                        $ventanillasEnUso[] = $ventanillaActual->getId();
                    }
                }
            }
            // listado de ventanillas del agente
            foreach ($agente->getVentanillas() as $ventanilla) {
                if (! in_array($ventanilla->getId(), $ventanillasEnUso)) {
                    $response[] = [
                        'id' => $ventanilla->getId(),
                        'identificador' => $ventanilla->getIdentificador()
                    ];
                }
            }

            return $this->respuestaData([], $response);
        }

        return $this->respuestaData([], null);
    }
}
