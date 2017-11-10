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
use ApiV1Bundle\Repository\PuntoAtencionRepository;

class ColaFactory
{
    private $colaValidator;
    private $colaRepository;
    private $puntoAtencionRepository;

    public function __construct(
        ColaValidator $colaValidator,
        ColaRepository $colaRepository,
        PuntoAtencionRepository $puntoAtencionRepository
    )
    {
        $this->colaValidator = $colaValidator;
        $this->colaRepository = $colaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function create($params)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoAtencion']);
        $validateResultado = $this->colaValidator->validarCreateByGrupoTramite($params, $puntoAtencion);

        if (! $validateResultado->hasError()) {

            $cola = new Cola(
                $params['nombre'],
                $puntoAtencion,
                Cola::TIPO_GRUPO_TRAMITE
            );

            $cola->setGrupoTramiteSNTId($params['grupoTramite']);

            $validateResultado->setEntity($cola);
        }

        return $validateResultado;
    }
}