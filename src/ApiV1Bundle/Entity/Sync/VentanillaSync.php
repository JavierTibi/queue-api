<?php

namespace ApiV1Bundle\Entity\Sync;


use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Entity\Validator\VentanillaValidator;
use ApiV1Bundle\Repository\ColaRepository;
use ApiV1Bundle\Repository\VentanillaRepository;

class VentanillaSync
{
    private $ventanillaValidator;
    private $ventanillaRepository;
    private $colaRepository;

    public function __construct(
        VentanillaValidator $ventanillaValidator,
        VentanillaRepository $ventanillaRepository,
        ColaRepository $colaRepository)
    {
        $this->ventanillaValidator = $ventanillaValidator;
        $this->ventanillaRepository = $ventanillaRepository;
        $this->colaRepository = $colaRepository;
    }

    /**
     * @param $id
     * @param $params
     * @return ValidateResultado
     */
    public function edit($id, $params)
    {
        $ventanilla = $this->ventanillaRepository->find($id);
        $validateResultado = $this->ventanillaValidator->validarEdit($ventanilla, $params);

        if (! $validateResultado->hasError()) {
            $ventanilla->setIdentificador($params['identificador']);

            //remove colas
            $colas = $ventanilla->getColas();
            foreach ($colas as $cola) {
                $ventanilla->removeCola($cola);
            }

            //add colas
            foreach ($params['colas'] as $colaId) {
                $cola = $this->colaRepository->find($colaId);
                $ventanilla->addCola($cola);
            }

            $validateResultado->setEntity($ventanilla);
        }

        return $validateResultado;
    }

    /**
     * Borra un agente
     * @param integer $id Identificador único
     *
     * @return ValidateResultado
     */
    public function delete($id)
    {
        $ventanilla = $this->ventanillaRepository->find($id);

        $validateResultado = $this->ventanillaValidator->validarVentanilla($ventanilla);

        if (! $validateResultado->hasError()) {
            $validateResultado->setEntity($ventanilla);
            return $validateResultado;
        }

        return $validateResultado;
    }
}