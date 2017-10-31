<?php

namespace ApiV1Bundle\Entity\Validator;


use ApiV1Bundle\Entity\Ventanilla;

class VentanillaValidator extends SNCValidator
{
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarParams($params) {
        $errors = $this->validar($params, [
            'puntoAtencion' => 'required',
            'identificador' => 'required',
            'colas' => 'required:matriz'
        ]);

        return new ValidateResultado(null, $errors);
    }

    /**
     * Validación del endpoint edit Ventanilla
     * @param $ventanilla
     * @param $params
     * @return ValidateResultado
     */
    public function validarEdit($ventanilla, $params) {
        $validateResultado = $this->validarVentanilla($ventanilla);

        if (! $validateResultado->hasError()) {
            $errors = $this->validar($params, [
                'identificador' => 'required',
                'colas' => 'required:matriz'
            ]);

            return new ValidateResultado(null, $errors);
        }

        return $validateResultado;
    }
}
