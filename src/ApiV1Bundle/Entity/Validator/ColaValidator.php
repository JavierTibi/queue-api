<?php

namespace ApiV1Bundle\Entity\Validator;

use ApiV1Bundle\Repository\PuntoAtencionRepository;

class ColaValidator extends SNCValidator
{

    public function validarParamsGet($puntoAtencionId){
        $errors = [];
        if (is_null($puntoAtencionId)) {
            $errors[] = 'El punto de atención es obligatorio.';
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarCreateByGrupoTramite($params, $puntoAtencion)
    {
        $errors = $this->validar($params, [
            'nombre' => 'required',
            'puntoAtencion' => 'required',
            'grupoTramite' => 'required:integer'
        ]);

        if (! count($errors) > 0 ) {
            return $this->validarPuntoAtencion($puntoAtencion);
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarEditByGrupoTramite($params, $cola)
    {
        $errors = $this->validar($params, [
            'nombre' => 'required'
        ]);

        if (! count($errors) > 0) {
            return $this->validarPuntoAtencion($cola);
        }

        return new ValidateResultado(null, $errors);
    }

}