<?php

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Factory\ColaFactory;
use ApiV1Bundle\Entity\Sync\ColaSync;
use ApiV1Bundle\Entity\Validator\ColaValidator;
use ApiV1Bundle\Entity\Validator\SNCValidator;
use ApiV1Bundle\Repository\ColaRepository;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use Symfony\Component\DependencyInjection\Container;

class ColaServices extends SNCServices
{
    private $colaValidator;
    private $colaRepository;
    private $puntoAtencionRepository;

    /**
     * ColaServices constructor.
     * @param Container $container
     * @param ColaValidator $colaValidator
     * @param ColaRepository $colaRepository
     * @param PuntoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        Container $container,
        ColaValidator $colaValidator,
        ColaRepository $colaRepository,
        PuntoAtencionRepository $puntoAtencionRepository
    )
    {
        parent::__construct($container);
        $this->colaValidator = $colaValidator;
        $this->colaRepository = $colaRepository;
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
        $validateResultado = $this->colaValidator->validarParamsGet($puntoAtencionId);

        if (! $validateResultado->hasError()) {
            $result = $this->colaRepository->findAllPaginate($puntoAtencionId, $offset, $limit);
            $resultset = [
                'resultset' => [
                    'count' => count($result),
                    'offset' => $offset,
                    'limit' => $limit
                ]
            ];
            return $this->respuestaData($resultset, $result);
        }

    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        $cola = $this->colaRepository->find($id);
        $validateResultado = $this->colaValidator->validarCola($cola);

        if (! $validateResultado->hasError()) {
            return $this->respuestaData([], $cola);
        }

        return $this->respuestaData([], null);
    }

    /**
     * Agrega una cola por grupo de tramite
     *
     * @param $params
     * @param $sucess
     * @param $error
     * @return mixed
     */
    public function addColaGrupoTramite($params, $sucess, $error)
    {
        $colaFactory = new ColaFactory(
            $this->colaValidator,
            $this->colaRepository,
            $this->puntoAtencionRepository
        );

        $validateResult = $colaFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->colaRepository->save($entity));
            },
            $error
        );

    }

    /**
     * Editar una cola de grupo tramite
     *
     * @param $params
     * @param $id
     * @param $success
     * @param $error
     * @return mixed
     */
    public function editColaGrupoTramite($params, $id, $success, $error)
    {
        $colaSync = new ColaSync(
            $this->colaValidator,
            $this->colaRepository
        );

        $validateResult = $colaSync->edit($id, $params);

        return $this->processResult(
            $validateResult,
            function () use ($success) {
                return call_user_func($success, $this->colaRepository->flush());
            },
            $error
        );
    }

    /**
     * Elimina una cola de un grupo de tramite
     *
     * @param integer $id Identificador único
     * @param $success | Indica si tuvo éxito o no
     * @param string $error Mensaje con el error ocurrido al eliminar
     * @return mixed
     */
    public function removeColaGrupoTramite($id, $success, $error)
    {
        $colaSync = new ColaSync(
            $this->colaValidator,
            $this->colaRepository
        );

        $validateResult = $colaSync->delete($id);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($success) {
                return call_user_func($success, $this->colaRepository->remove($entity));
            },
            $error
        );
    }
}