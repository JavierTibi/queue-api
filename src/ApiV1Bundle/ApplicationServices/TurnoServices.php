<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:26 PM
 */

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Turno;
use ApiV1Bundle\Entity\TurnoFactory;
use ApiV1Bundle\Entity\Validator\TurnoValidator;
use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\ExternalServices\SNCExternalService;
use ApiV1Bundle\ExternalServices\SNTTurnosService;
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
    private $redisServices;
    private $turnoIntegration;
    private $ventanillaRepository;

    /**
     * TurnoServices constructor.
     * @param Container $container
     * @param TurnoRepository $turnoRepository
     * @param TurnoValidator $turnoValidator
     * @param PuntoAtencionRepository $puntoAtencionRepository
     * @param ColaRepository $colaRepository
     * @param RedisServices $redisServices
     * @param SNTTurnosService $turnoIntegration
     * @param VentanillaRepository $ventanillaRepository
     */
    public function __construct(
        Container $container,
        TurnoRepository $turnoRepository,
        TurnoValidator $turnoValidator,
        PuntoAtencionRepository $puntoAtencionRepository,
        ColaRepository $colaRepository,
        RedisServices $redisServices,
        SNTTurnosService $turnoIntegration,
        VentanillaRepository $ventanillaRepository
    )
    {
        parent::__construct($container);
        $this->turnoRepository = $turnoRepository;
        $this->turnoValidator = $turnoValidator;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->colaRepository = $colaRepository;
        $this->redisServices = $redisServices;
        $this->turnoIntegration = $turnoIntegration;
        $this->ventanillaRepository = $ventanillaRepository;
    }

    /**
     * Importa un nuevo turno del SNT
     *
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
            $turno = $validateResult->getEntity();
            $validateRedis = $this->recepcionarTurno($turno);

            if($validateRedis->hasError()) {
                return $validateRedis;
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

    /**
     * Cambia el estado del Turno luego de ser atendido
     *
     * @param $params
     * @param $sucess
     * @param $error
     * @return ValidateResultado|mixed
     */
    public function changeStatus($params, $sucess, $error)
    {
        $turnoFactory = new TurnoFactory(
            $this->turnoRepository,
            $this->turnoValidator,
            $this->puntoAtencionRepository
        );

        $validateResult = $turnoFactory->changeStatus($params);

        if (! $validateResult->hasError()) {
            $turno = $validateResult->getEntity();
            if ($turno->getEstado() == Turno::ESTADO_RECEPCIONADO) {
                $validateRedis = $this->recepcionarTurno($turno);

                if($validateRedis->hasError()) {
                    return $validateRedis;
                }
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

    /**
     * @param Turno $turno
     * @return ValidateResultado
     */
    private function recepcionarTurno($turno)
    {
        $cola = $this->colaRepository->findOneBy(['grupoTramiteSNTId' => $turno->getGrupoTramiteIdSNT()]);
        return $this->redisServices->zaddCola($turno->getPuntoAtencion()->getId(), $cola->getId(), $turno->getPrioridad(), $turno->getCodigo());
    }

    /**
     * Obtiene el listado de turnos del SNT
     *
     * @param $params
     * @return ValidateResultado|object
     */
    public function getListTurnosSNT($params)
    {
        $validateResult = $this->turnoValidator->validarGetSNT($params);

        if (! $validateResult->hasError()) {

            $result = $this->turnoIntegration->getListTurnos($params);
            $result->metadata->resultset = (array) $result->metadata->resultset;

            return $this->respuestaData((array) $result->metadata, $result->result);
        }

        return $validateResult;
    }

    /**
     * Obtiene un turno por ID
     *
     * @param $id
     * @return object
     */
    public function getItemTurnoSNT($id)
    {
        $result = $this->turnoIntegration->getItemTurnoSNT($id);

        return $this->respuestaData([], $result);
    }


    public function findAllPaginate($params)
    {
        $validateResult = $this->turnoValidator->validarGetRecepcionados($params);

        if (! $validateResult->hasError()) {
            $ventanilla = $this->ventanillaRepository->find($params['ventanilla']);

            $colas = $ventanilla->getColas();

            if($colas->count() == 1) {

                $this->redisServices->getCola($params['puntoatencion'], $colas->first()->getId());

            } elseif ($colas->count() > 1) {

                $validateCola = $this->redisServices->unionColas($params['puntoatencion'], $colas, $ventanilla);
                if(! $validateCola->hasError()) {
                    $codigosTurno = $this->redisServices->getColaVentanilla($params['puntoatencion'], $ventanilla);
                    $result = $this->searchTurnosByCodigo($params['puntoatencion'], $codigosTurno);
                    $resultset = [
                        'resultset' => [
                            'count' => 100,
                            'offset' => $params['offset'],
                            'limit' => $params['limit']
                        ]
                    ];
                    return $this->respuestaData($resultset, $result);
                }
                return $validateCola;

            } else {
                // LA VENTANILLA NO TIENE COLA
            }
        }

    }

    /**
     * @param array $codigoTurnos
     */
    private function searchTurnosByCodigo($puntoAtencionId, $codigoTurnos)
    {
        return $this->turnoRepository->getTurnosByCodigos($puntoAtencionId, $codigoTurnos);
    }

}
