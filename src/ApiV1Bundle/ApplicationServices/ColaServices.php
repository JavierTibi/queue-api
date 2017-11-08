<?php

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Validator\ColaValidator;
use ApiV1Bundle\Entity\Validator\SNCValidator;
use ApiV1Bundle\Repository\ColaRepository;

class ColaServices extends SNCValidator
{
    private $colaValidator;
    private $colaRepository;

    /**
     * ColaServices constructor.
     * @param ColaValidator $colaValidator
     * @param ColaRepository $colaRepository
     */
    public function __construct(
        ColaValidator $colaValidator,
        ColaRepository $colaRepository
    )
    {
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
}