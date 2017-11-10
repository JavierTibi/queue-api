<?php

namespace ApiV1Bundle\Entity\Sync;


use ApiV1Bundle\Entity\Validator\ColaValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\ColaRepository;

class ColaSync
{
    private $colaValidator;
    private $colaRepository;

    public function __construct(ColaValidator $colaValidator, ColaRepository $colaRepository)
    {
        $this->colaValidator = $colaValidator;
        $this->colaRepository = $colaRepository;
    }

    /**
     * Edita una cola grupo tramite
     * @param $id
     * @param $params
     * @return ValidateResultado
     */
    public function edit($id, $params)
    {
        $cola = $this->colaRepository->findOneBy(['grupoTramiteSNTId' => $id]);
        $validateResultado = $this->colaValidator->validarEditByGrupoTramite($params, $cola);

        if (! $validateResultado->hasError()) {
            $cola->setNombre($params['nombre']);
            $validateResultado->setEntity($cola);
        }

        return $validateResultado;
    }

    /**
     * Borra una cola grupo tramite
     * @param integer $id Identificador único
     *
     * @return ValidateResultado
     */
    public function delete($id)
    {
        $cola = $this->colaRepository->findOneBy(['grupoTramiteSNTId' => $id]);

        $validateResultado = $this->colaValidator->validarCola($cola);

        if (! $validateResultado->hasError()) {
            $validateResultado->setEntity($cola);
        }

        return $validateResultado;
    }
}