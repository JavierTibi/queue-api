<?php

namespace ApiV1Bundle\Entity\Validator;

class ColaValidator extends SNCValidator
{
    public function validarParamsGet($puntoAtencionId){
        $errors = [];
        if (is_null($puntoAtencionId)) {
            $errors[] = "El punto de atención es obligatorio.";
        }
        //TODO validar Punto de Atencion

        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarCreateByGrupoTramite($params)
    {
        $errors = [];

        if (! isset($params["puntoAtencion"])) {
            //TODO validar Punto de Atencion - find
            $errors[] = "El punto de atención es obligatorio.";
            return new ValidateResultado(null, $errors);
        }

        if (! isset($params["puntoAtencion"])) {
            $errors[] = "El nombre del grupo tramite es es obligatorio.";
            return new ValidateResultado(null, $errors);
        }

        return new ValidateResultado(null, $errors);
    }
}