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
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarCreate($params)
    {
        $errors = $this->validar($params, [
            'puntoAtencion' => 'required:integer',
            'tramite' => 'required',
	        'grupoTramite' => 'required:integer',
            'fecha' => 'required:dateTZ',
            'hora' =>  'required:time',
            'estado' => 'required',
	        'codigo' => 'required',
	        'datosTurno' => 'required',
            'prioridad' => 'required:integer'
        ]);

        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarChangeStatus($params)
    {
        $errors = $this->validar($params, [
            'cuil' => 'required',
            'codigo' => 'required',
            'estado' => 'required:integer'
        ]);

        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarGetSNT($params)
    {
        $errors = $this->validar($params, [
            'fecha' => 'required',
            'puntoatencion' => 'required'
        ]);

        return new ValidateResultado(null, $errors);
    }
}
