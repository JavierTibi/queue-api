<?php

namespace ApiV1Bundle\Entity\Validator;

use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\PuntoAtencionRepository;

class PuntoAtencionValidator extends SNCValidator
{
    private $puntoAtencionRepository;
    
    /**
     * PuntoAtencionValidator constuct
     * @param PuntoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        PuntoAtencionRepository $puntoAtencionRepository
    ) 
    {
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        
    }
    
    public function validarCrear($params) {
        $error = [];
        $puntoAtencion = null;
        
        $error = $this->validar($params, [
            'punto_atencion_id_SNT' => 'required',
            'nombre' => 'required',
        ]);
        
        if(isset($params['punto_atencion_id_SNT'])){
            $puntoAtencion = $this->puntoAtencionRepository->findBypuntoAtencionIdSnt($params['punto_atencion_id_SNT']);
        }
        
        if($puntoAtencion){
            $error['PuntoAtencion'] = 'El punto de atención ya existe';
        }
        return new ValidateResultado(null, $error);
    }
    
    /**
     * Validar campos a editar y punto de atencion
     * 
     * @param type $id Id del punto de atencion a editar
     * @param type $params Array de parametros a modificar
     * @return ValidateResultado
     */
    public function validarEditar($puntoAtencion, $params) {
        $error = [];
        $error = $this->validar($params, [
            'nombre' => 'required',
        ]);
        
        if ($puntoAtencion && ! count($error)) {
            return new ValidateResultado($puntoAtencion, []);
        }else if ( !$puntoAtencion ){
            return $this->validarPuntoAtencion($puntoAtencion);
        }
        
        return new ValidateResultado(null, $error);
    }
    /**
     * Valida el borrado de un punto de atención
     *
     * @param $puntoAtencion Entidad Punto de atencion
     * @return ValidateResultado
     */
    public function validarDelete($puntoAtencion)
    {
        return $this->validarPuntoAtencion($puntoAtencion);
        
    }
}
