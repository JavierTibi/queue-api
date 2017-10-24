<?php

namespace ApiV1Bundle\Entity\Validator;


class VentanillaValidator extends SNCValidator
{
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarParams($params) {
        $errors = $this->validar($params, [
            'identificador' => 'required'
        ]);

        return new ValidateResultado(null, $errors);
    }
}