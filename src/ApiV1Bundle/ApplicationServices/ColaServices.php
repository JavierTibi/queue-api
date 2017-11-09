<?php

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Factory\ColaFactory;
use ApiV1Bundle\Entity\Validator\ColaValidator;
use ApiV1Bundle\Entity\Validator\SNCValidator;
use ApiV1Bundle\Repository\ColaRepository;
use Symfony\Component\DependencyInjection\Container;

class ColaServices extends SNCServices
{
    private $colaValidator;
    private $colaRepository;

    /**
     * ColaServices constructor.
     * @param ColaValidator $colaValidator
     * @param ColaRepository $colaRepository
     */
    public function __construct(
        Container $container,
        ColaValidator $colaValidator,
        ColaRepository $colaRepository
    )
    {
        parent::__construct($container);
        $this->colaValidator = $colaValidator;
        $this->colaRepository = $colaRepository;
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
     * @param $redis
     * @param $sucess
     * @param $error
     * @return mixed
     */
    public function addColaGrupoTramite($params, $sucess, $error)
    {
        $ventanillaFactory = new ColaFactory(
            $this->colaValidator,
            $this->colaRepository
        );

        $validateResult = $ventanillaFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->colaRepository->save($entity));
            },
            $error
        );

    }
}