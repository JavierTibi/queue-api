<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:26 PM
 */

namespace ApiV1Bundle\Entity;



use ApiV1Bundle\Entity\Validator\TurnoValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Repository\TurnoRepository;

class TurnoFactory
{

    private $turnoRepository;
    private $turnoValidator;
    private $puntoAtencionRepository;

    /**
     * TurnoServices constructor.
     * @param TurnoRepository $turnoRepository
     * @param TurnoValidator $turnoValidator
     * @param PuntoAtencionRepository $puntoAtencionRepository
     */
    public function __construct(
        TurnoRepository $turnoRepository,
        TurnoValidator $turnoValidator,
        PuntoAtencionRepository $puntoAtencionRepository
    )
    {
        $this->turnoRepository = $turnoRepository;
        $this->turnoValidator = $turnoValidator;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $validateResultado = $this->turnoValidator->validarCreate($params);

        if (! $validateResultado->hasError()) {

            $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoAtencion']);
            $fecha = new \DateTime($params['fecha']);
            $hora = new \DateTime($params['hora']);

            $datosTurno = new DatosTurno(
                $params['datosTurno']['nombre'],
                $params['datosTurno']['apellido'],
                $params['datosTurno']['cuil'],
                $params['datosTurno']['email'],
                $params['datosTurno']['telefono'],
                $params['datosTurno']['campos']
            );

            $this->turnoRepository->persist($datosTurno);

            $turno = new Turno(
                $puntoAtencion,
                $datosTurno,
                $params['grupoTramite'],
                $fecha,
                $hora,
                $params['estado'],
                $params['tramite'],
                $params['codigo'],
                $params['prioridad']
            );

            if (isset($params['motivo'])) {
                $turno->setMotivoTerminado($params['motivo']);
            }

            return new ValidateResultado($turno, []);
        }

        return $validateResultado;
    }

    /**
     * @param $params
     * @return ValidateResultado
     */
    public function changeStatus($params)
    {
        $validateResultado = $this->turnoValidator->validarChangeStatus($params);

        if (! $validateResultado->hasError()) {
            $turno = $this->turnoRepository->search($params['cuil'], $params['codigo']);
            $validateResultado = $this->turnoValidator->validarTurno($turno);
            if (! $validateResultado->hasError()) {
                $newTurno = new Turno(
                    $turno->getPuntoAtencion(),
                    $turno->getDatosTurno(),
                    $turno->getGrupoTramiteIdSNT(),
                    $turno->getFecha(),
                    $turno->getHora(),
                    $params['estado'],
                    $turno->getTramite(),
                    $params['codigo'],
                    $params['prioridad']
                );

                if (isset($params['motivo'])) {
                    $newTurno->setMotivoTerminado($params['motivo']);
                }

                return new ValidateResultado($newTurno, []);
            }
        }

        return $validateResultado;

    }
}