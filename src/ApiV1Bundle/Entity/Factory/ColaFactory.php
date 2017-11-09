<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 8/11/2017
 * Time: 3:15 PM
 */

namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Cola;
use ApiV1Bundle\Entity\Validator\ColaValidator;
use ApiV1Bundle\Repository\ColaRepository;

class ColaFactory
{
    private $colaValidator;
    private $colaRepository;

    public function __construct(ColaValidator $colaValidator, ColaRepository $colaRepository)
    {
        $this->colaValidator = $colaValidator;
        $this->colaRepository = $colaRepository;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function create($params)
    {
        $validateResultado = $this->colaValidator->validarCreateByGrupoTramite($params);

        if (! $validateResultado->hasError()) {

            $cola = new Cola(
                $params['nombre'],
                $params['puntoAtencion'], //TODO find punto atencion
                Cola::TIPO_GRUPO_TRAMITE
            );

            $validateResultado->setEntity($cola);
        }

        return $validateResultado;
    }
}