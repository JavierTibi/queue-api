<?php

namespace ApiV1Bundle\Entity\Validator;


use ApiV1Bundle\Repository\UserRepository;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Entity\User;

class UserValidator extends SNCValidator
{
    private $userRepository;
    private $encoder;

    public function __construct(UserRepository $userRepository, $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * Validar que exista usuario y contraseña
     *
     * @param $params
     * @return ValidateResultado
     */
    public function validarParamsLogin($params, $user)
    {
        $errors = $this->validar($params, [
            'username' => 'required',
            'password' => 'required'
        ]);
        if (! count($errors) && ! $user) {
            $errors['error'] = 'Usuario/contraseña incorrectos';
        }
        return new ValidateResultado(null, $errors);
    }

    /**
     * Validar el login del usuario
     *
     * @param $enconder
     * @param $user
     * @param $password
     * @return ValidateResultado
     */
    public function validarLogin($user, $password)
    {
        $errors = [];
        if (! $this->encoder->isPasswordValid($user, $password)) {
            $errors[] = 'Usuario/contraseña incorrectos';
        }
        return new ValidateResultado(null, $errors);
    }

    public function validarCreate($params)
    {
        $rules = [
            'rol' => 'required',
            'username' => 'required:email',
            'nombre' => 'required'
        ];
        if (isset($params['rol']) && $params['rol'] == User::ROL_AGENTE) {
            $rules['ventanillas'] = 'required:matriz';
        }
        $errors = $this->validar($params, $rules);
        return new ValidateResultado(null, $errors);
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    private function validarParams($params)
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