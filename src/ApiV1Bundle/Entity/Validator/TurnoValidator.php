<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 8:04 PM
 */

namespace ApiV1Bundle\Entity\Validator;


class TurnoValidator extends SNCValidator
{
    public function validarCreate($params)
    {
        $errors = $this->validar($params, [
            'puntoatencion' => 'required:integer',
            'tramite' => 'required',
	        'grupoTramite' => 'required:integer',
            'fecha' => 'required:dateTZ',
            'hora' =>  'required:time',
            'estado' => 'required',
	        'codigo' => 'required',
	        'datosTurno' => 'required'
        ]);

        return new ValidateResultado(null, $errors);
    }
}