<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:26 PM
 */

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\TurnoFactory;
use ApiV1Bundle\Entity\Validator\TurnoValidator;
use ApiV1Bundle\Repository\ColaRepository;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\TurnoRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

class TurnoServices extends SNCServices
{
    private $turnoRepository;
    private $turnoValidator;
    private $puntoAtencionRepository;
    private $colaRepository;

    /**
     * TurnoServices constructor.
     * @param Container $container
     * @param TurnoRepository $turnoRepository
     * @param TurnoValidator $turnoValidator
     * @param PuntoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        Container $container,
        TurnoRepository $turnoRepository,
        TurnoValidator $turnoValidator,
        PuntoAtencionRepository $puntoAtencionRepository,
        ColaRepository $colaRepository
    )
    {
        parent::__construct($container);
        $this->turnoRepository = $turnoRepository;
        $this->turnoValidator = $turnoValidator;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->colaRepository = $colaRepository;
    }

    /**
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        $turnoFactory = new TurnoFactory(
            $this->turnoRepository,
            $this->turnoValidator,
            $this->puntoAtencionRepository
        );

        $validateResult = $turnoFactory->create($params);

        if (! $validateResult->hasError()) {
            $cola = $this->colaRepository->findOneByGrupoTramiteIdSNT($params['grupoTramite']);

            $fecha = new \DateTime();
            $val = $this->getContainerRedis()->zadd(
                'puntoAtencion:' . $params['puntoAtencion'] . ':cola:' . $cola->getId() . ':prioridad:' . $params['prioridad'],
                $fecha->getTimestamp(),
                $validateResult->getEntity()->getCodigo()
            );

            if ($val != 1) {
                $erros = ['No se ha podido crear la cola'];
                $validateResult->setErrors($error);
            }
        }

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->turnoRepository->save($entity));
            },
            $error
        );
    }
}