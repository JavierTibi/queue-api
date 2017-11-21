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

    /**
     * Validamos los parametors de la busqueda de turnos
     *
     * @param $params
     * @return ValidateResultado
     */
    public function validarSearchSNT($params)
    {
        $errors = $this->validar($params, [
            'codigo' => 'required'
        ]);

        return new ValidateResultado(null, $errors);
    }

    /**
     * Validamos el turno recepcionado
     *
     * @param unknown $params
     * @param unknown $ventanilla
     * @return ValidateResultado
     */
    public function validarGetRecepcionados($params, $ventanilla)
    {
        $errors = $this->validar($params, [
            'puntoatencion' => 'required:integer',
            'ventanilla' => 'required:integer'
        ]);

        $validateVentanilla = $this->validarVentanilla($ventanilla);

        if ($validateVentanilla->hasError()) {
            return $validateVentanilla;
        }

        return new ValidateResultado(null, $errors);
    }
}
