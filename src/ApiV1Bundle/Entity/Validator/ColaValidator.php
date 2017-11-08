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
}