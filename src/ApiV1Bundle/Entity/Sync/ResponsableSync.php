<?php

namespace ApiV1Bundle\Entity\Sync;


use ApiV1Bundle\Entity\Agente;
use ApiV1Bundle\Repository\ResponsableRepository;
use ApiV1Bundle\Repository\UserRepository;
use ApiV1Bundle\Entity\Validator\ResponsableValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;

/**
 * Class ResponsableSync
 * @package ApiV1Bundle\Entity\Sync
 */
class ResponsableSync
{
    private $userValidator;
    private $responsableRepository;
    private $responsableValidator;

    /**
     * ResponsableSync constructor.
     * @param UserValidator $userValidator
     * @param ResponsableRepository $responsableRepository
     * @param ResponsableValidator $responsableValidator
     */
    public function __construct(
        UserValidator $userValidator,
        ResponsableRepository $responsableRepository,
        ResponsableValidator $responsableValidator)
    {
        $this->userValidator = $userValidator;
        $this->responsableRepository = $responsableRepository;
        $this->responsableValidator = $responsableValidator;
    }

    public function edit($id, $params)
    {
        $validateResultado = $this->responsableValidator->validarParams($params);

        if (! $validateResultado->hasError()) {

            $responsable = $this->responsableRepository->findOneByUser($id);
            $user = $responsable->getUser();

            $responsable->setNombre($params['nombre']);
            $responsable->setApellido($params['apellido']);
            $responsable->setPuntoAtencion($params['puntoAtencion']);

            if (isset($params['username'])) {
                $user->setUsername($params['username']);
            }

            if (isset($params['password'])) {
                $user->setPassword($params['password']);
            }

            $validateResultado->setEntity($responsable);
        }

        return $validateResultado;
    }

    /**
     * Borra un responsable
     * @param integer $id Identificador único del responsable
     *
     * @return ValidateResultado
     */
    public function delete($id)
    {
        $responsable = $this->responsableRepository->findOneByUser($id);

        $validateResultado = $this->userValidator->validarUsuario($responsable);

        if (! $validateResultado->hasError()) {
            $validateResultado->setEntity($responsable);
        }

        return $validateResultado;
    }
}
