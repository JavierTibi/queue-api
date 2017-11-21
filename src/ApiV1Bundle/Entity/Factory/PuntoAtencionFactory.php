<?php
namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Entity\Validator\PuntoAtencionValidator;
use ApiV1Bundle\Entity\PuntoAtencion;
use ApiV1Bundle\Repository\PuntoAtencionRepository;

class PuntoAtencionFactory
{
    private $puntoAtencionValidator;
    private $puntoAtencionRepository;
    
    /**
     * 
     * @param PuntoAtencionValidator $puntoAtencionValidator
     * @param \ApiV1Bundle\Entity\Factory\untoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        PuntoAtencionRepository $puntoAtencionRepository,
        PuntoAtencionValidator $puntoAtencionValidator
    )
    {
        $this->puntoAtencionValidator = $puntoAtencionValidator;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }
    /**
     * Crear punto de atencion
     * 
     * @param $params Array con los datos para crear punto de atencion
     * @return ValidateResultado
     */
    public function create($params)
    {
        $errores = $this->puntoAtencionValidator->validarCrear($params);
        
        if (! $errores->hasError()) {
            $puntoAtencion = new PuntoAtencion();
            $puntoAtencion->setPuntoAtencionIdSnt($params['punto_atencion_id_SNT']);
            $puntoAtencion->setNombre($params['nombre']);
            return new ValidateResultado($puntoAtencion, []);
        }

        return new ValidateResultado(null, $errores->getErrors());
    }
}