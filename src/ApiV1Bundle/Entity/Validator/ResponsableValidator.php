<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 19/10/2017
 * Time: 2:56 PM
 */

namespace ApiV1Bundle\Entity\Validator;

use ApiV1Bundle\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

class ResponsableValidator extends SNCValidator
{

    private $userRepository;

    /**
     * ResponsableValidator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Validamos los parametros del front
     *
     * @param $id
     * @param $params
     * @return \ApiV1Bundle\Entity\Validator\ValidateResultado
     */
    public function validarParams($id, $params)
    {
        $errors = $this->validar($params, [
            'nombre' => 'required',
            'apellido' => 'required',
            'puntoAtencion' => 'required:integer'
        ]);

        if (! count($errors) > 0) {
            $user = $this->userRepository->findOneById($id);
            if ($user && $user->getUsername() != $params['username']) {
                $otherUser = $this->userRepository->findOneByUsername($params['username']);
                if ($otherUser) {
                    $errors['User'] = 'Ya existe un usuario con el email ingresado.';
                    return new ValidateResultado(null, $errors);
                }
            }

            if (! in_array((int) $params['rol'], [1,2,3], true)) {
                $errors['Rol'] = 'Rol inexistente.';
                return new ValidateResultado(null, $errors);
            }
        }

        return new ValidateResultado(null, $errors);
    }
}
