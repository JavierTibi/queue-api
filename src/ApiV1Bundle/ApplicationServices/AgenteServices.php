<?php

namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Factory\AgenteFactory;
use ApiV1Bundle\Entity\Sync\AgenteSync;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\VentanillaValidator;
use ApiV1Bundle\Repository\AgenteRepository;
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
    private $userValidator;
    private $ventanillaRepository;

    public function __construct(
        Container $container,
        AgenteRepository $agenteRepository,
        AgenteValidator $agenteValidator,
        UserValidator $userValidator,
        VentanillaRepository $ventanillaRepository
    )
    {
        parent::__construct($container);
        $this->agenteRepository = $agenteRepository;
        $this->agenteValidator = $agenteValidator;
        $this->userValidator = $userValidator;
        $this->ventanillaRepository = $ventanillaRepository;
    }

    /**
     * @param $puntoAtencionId
     * @param $limit
     * @param $offset
     * @return object
     */
    public function findAllPaginate($puntoAtencionId, $limit, $offset)
    {
        $result = $this->agenteRepository->findAllPaginate($puntoAtencionId, $offset, $limit);
        $resultset = [
            'resultset' => [
                'count' => count($result),
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
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        $agenteFactory = new AgenteFactory(
            $this->agenteValidator,
            $this->userValidator,
            $this->ventanillaRepository
        );

        $validateResult = $agenteFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->agenteRepository->save($entity));
            },
            $error
        );
    }

    /**
     * Editar un usuario Agente
     *
     * @param $params
     * @param $id
     * @param $success
     * @param $error
     * @return mixed
     */
    public function edit($params, $id, $success, $error)
    {
        $agenteSync = new AgenteSync(
            $this->agenteValidator,
            $this->agenteRepository,
            $this->ventanillaRepository
        );

        $validateResult = $agenteSync->edit($id, $params);
        return $this->processResult(
            $validateResult,
            function ($entity) use ($success) {
                return call_user_func($success, $this->agenteRepository->flush());
            },
            $error
        );
    }

    /**
     * Elimina un agente
     *
     * @param integer $id Identificador único del área
     * @param $success | Indica si tuvo éxito o no
     * @param string $error Mensaje con el error ocurrido al borrar un área
     * @return mixed
     */
    public function delete($id, $success, $error)
    {
        $agenteSync = new AgenteSync(
            $this->agenteValidator,
            $this->agenteRepository,
            $this->ventanillaRepository
        );

        $validateResult = $agenteSync->delete($id);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($success) {
                return call_user_func($success, $this->agenteRepository->remove($entity));
            },
            $error
        );
    }

    /**
     * @param $idAgente
     * @param $idVentanilla
     * @param $success
     * @param $error
     * @return mixed
     */
    public function asignarVentanilla($idAgente, $idVentanilla, $success, $error)
    {
        $agenteSync = new AgenteSync(
            $this->agenteValidator,
            $this->agenteRepository,
            $this->ventanillaRepository
        );

        $validateResult = $agenteSync->asignarVentanilla($idAgente, $idVentanilla);

        return $this->processResult(
            $validateResult,
            function () use ($success) {
                return call_user_func($success, $this->agenteRepository->flush());
            },
            $error
        );

    }
}
