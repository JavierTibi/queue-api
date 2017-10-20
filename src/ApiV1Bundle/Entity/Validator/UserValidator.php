<?php

namespace ApiV1Bundle\Entity\Validator;


class UserValidator extends SNCValidator
{
    public function validarParams($params)
    {
        $errors = $this->validar($params, [
            'username' => 'required',
            'password' => 'required'
        ]);

        //TODO validar si el usuario no existe

        return new ValidateResultado(null, $errors);
    }
}