<?php

namespace ApiV1Bundle\Entity\Sync;

use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Entity\Validator\PuntoAtencionValidator;

class PuntoAtencionSync
{
    private $puntoAtencionRepository;
    private $puntoAtencionValidator;

    /**
     * PuntoAtencionSync constructor.
     * @param PuntoAtencionRepository $puntoAtencionRepository
     * @param PuntoAtencionValidator $puntoAtencionValidator
     */
    public function __construct(
        PuntoAtencionRepository $puntoAtencionRepository,
        PuntoAtencionValidator $puntoAtencionValidator
    ) {
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->puntoAtencionValidator = $puntoAtencionValidator;
    }
    /**
     * Editar punto de atencion
     *
     * @param integer $id Identificador único para un punto de atención
     * @param array $params array con los datos para editar el punto de atención
     * @return ValidateResultado
     */
    public function edit($id, $params)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($id);
        $errores = $this->puntoAtencionValidator->validarEditar($puntoAtencion, $params);
        
        if (! $errores->hasError()) {
            $puntoAtencion->setNombre($params['nombre']);
            return new ValidateResultado($puntoAtencion, []);
        }
        
        return new ValidateResultado(null, $errores->getErrors());
    }

    /**
     * Eliminar punto de atención
     *
     * @param integer $id Identificador único para un punto de atención
     * @return ValidateResultado
     */
    public function delete($id)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($id);
        $validateResultado = $this->puntoAtencionValidator->validarDelete($puntoAtencion);
        if (!$validateResultado->hasError()) {
            return new ValidateResultado($puntoAtencion, []);
        }

        return new ValidateResultado(null, $validateResultado->getErrors());
    }
}
