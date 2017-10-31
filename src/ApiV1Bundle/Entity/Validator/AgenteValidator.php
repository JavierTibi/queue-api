<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 19/10/2017
 * Time: 2:56 PM
 */

namespace ApiV1Bundle\Entity\Validator;


use Doctrine\Common\Collections\ArrayCollection;

class AgenteValidator extends SNCValidator
{


    public function validarParams($params)
    {
        $errors = $this->validar($params, [
            'nombre' => 'required',
            'apellido' => 'required',
            'puntoAtencion' => 'required:integer',
            'ventanillas' => 'required:matriz'
        ]);

        //TODO descomentar las validaciones cuando se creen los Repositorys de ventanilla y punto de atencion
        /*
         * $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoatencion']);

        if (! $puntoAtencion) {
            $errors['Punto Atencion'] = 'Punto de atención inexistente.';
        }

        foreach ($params['ventanillas'] as $idVentanilla) {
            $ventanilla = $this->ventanillaRepository->find($idVentanilla);

            if(! $ventanilla) {
                $errors['Ventanilla'][] = 'La ventanilla con ID: ' . $idVentanilla. 'no fue encontrada.';
            }
        }*/


        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $agente
     * @param $ventanilla
     * @return ValidateResultado
     */
    public function validarAsignarVentanilla($agente, $ventanilla)
    {
        $validateResultadoAgente = $this->validarAgente($agente);

        if($validateResultadoAgente->hasError()) {
            return $validateResultadoAgente;
        }

        $validateResultadoVentanilla = $this->validarVentanilla($ventanilla);

        if($validateResultadoVentanilla->hasError()) {
            return $validateResultadoVentanilla;
        }

        return new ValidateResultado(null, []);
    }
}