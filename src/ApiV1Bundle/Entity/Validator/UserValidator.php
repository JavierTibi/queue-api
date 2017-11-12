<?php

namespace ApiV1Bundle\Entity\Validator;


use ApiV1Bundle\Repository\UserRepository;

class UserValidator extends SNCValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarParams($params)
    {
        $errors = $this->validar($params, [
            'username' => 'required:email',
            'rol' => 'required:integer'
        ]);

        if (! count($errors) > 0) {
            $user = $this->userRepository->findOneByUsername($params['username']);
            if ($user) {
                $errors['User'] = 'Ya existe un usuario con el email ingresado';
                return new ValidateResultado(null, $errors);
            }

            if (! in_array((int) $params['rol'], [1,2,3], true)) {
                $errors['Rol'] = 'Rol inexistente.';
                return new ValidateResultado(null, $errors);
            }
        }

        return new ValidateResultado(null, $errors);
    }

    /**
     * Validar parametros para la creación de un Agente
     * @param $params
     * @return ValidateResultado
     */
    public function validarParamsAgente($params, $puntoAtencion)
    {
        $validateResultado = $this->validarParams($params);

        if (! $validateResultado->hasError()) {
            $errors = $this->validar($params, [
                'nombre' => 'required',
                'apellido' => 'required',
                'puntoAtencion' => 'required:integer',
                'ventanillas' => 'required:matriz'
            ]);

            if (! count($errors) > 0) {
                return $this->validarPuntoAtencion($puntoAtencion);
            }

            return new ValidateResultado(null, $errors);

            //TODO descomentar las validaciones cuando se creen los Repositorys de ventanilla
            /*
            foreach ($params['ventanillas'] as $idVentanilla) {
                $ventanilla = $this->ventanillaRepository->find($idVentanilla);

                if(! $ventanilla) {
                    $errors['Ventanilla'][] = 'La ventanilla con ID: ' . $idVentanilla. 'no fue encontrada.';
                }
            }*/

        }

        return $validateResultado;
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

    /**
     * Validar parametros para la creación de un Agente
     * @param $params
     * @return ValidateResultado
     */
    public function validarParamsResponsable($params, $puntoAtencion)
    {
        $validateResultado = $this->validarParams($params);

        if (! $validateResultado->hasError()) {
            $errors = $this->validar($params, [
                'nombre' => 'required',
                'apellido' => 'required',
                'puntoAtencion' => 'required:integer'
            ]);

            if (! count($errors) > 0) {
                return $this->validarPuntoAtencion($puntoAtencion);
            }

            return new ValidateResultado(null, $errors);
        }

        return $validateResultado;
    }

    /**
     * Validar parametros para la creación de un Agente
     * @param $params
     * @return ValidateResultado
     */
    public function validarParamsAdmin($params)
    {
        $validateResultado = $this->validarParams($params);

        if (! $validateResultado->hasError()) {
            $errors = $this->validar($params, [
                'nombre' => 'required',
                'apellido' => 'required'
            ]);

            return new ValidateResultado(null, $errors);
        }

        return $validateResultado;
    }

}