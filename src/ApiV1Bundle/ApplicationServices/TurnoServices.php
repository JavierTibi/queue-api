<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:26 PM
 */

namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Getter\TurnoGetter;
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
use Symfony\Component\HttpFoundation\Request;
use ApiV1Bundle\Helper\ServicesHelper;

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
    ) {
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

            if ($validateRedis->hasError()) {
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

                if ($validateRedis->hasError()) {
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
        return $this->redisServices->zaddCola(
            $turno->getPuntoAtencion()->getId(),
            $cola->getId(),
            $turno->getPrioridad(),
            $turno
        );
    }

    /**
     * Obtiene el listado de turnos del SNT
     *
     * @param $params
     * @return ValidateResultado|object
     */
    public function getListTurnosSNT($params, $onError)
    {
        $response = [];
        $validateResultado = $this->turnoValidator->validarGetSNT($params);

        if (! $validateResultado->hasError()) {
            $validateResultado = $this->turnoIntegration->getListTurnos($params);

            if (! $validateResultado->hasError()) {
                $response = $validateResultado->getEntity();
                $response->metadata->resultset = (array) $response->metadata->resultset;
                //transforma el resultado en array para enviarlo a Respuesta Data.
                foreach ($response->result as $item) {
                    $item->campos =  (array) $item->campos;
                }
            }
        }

        return $this->processError(
            $validateResultado,
            function () use ($response) {
                return $this->respuestaData((array)$response->metadata, $this->toArray($response->result));
            },
            $onError
        );
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

    /**
     * Busqueda de turnos por código
     *
     * @param $params
     * @return Respuesta|ValidateResultado
     */
    public function searchTurnoSNT($params, $success, $error)
    {
        $validateResult = $this->turnoValidator->validarSearchSNT($params);

        if (! $validateResult->hasError()) {
            $validateResult = $this->turnoIntegration->searchTurnoSNT($params['codigo']);
            $result = $validateResult->getEntity();
            if ($result) {
                // @ToDo cambiar la transformación de objeto a array
                $result->punto_atencion = (array) $result->punto_atencion;
                $result->tramite = (array) $result->tramite;
                $result->grupo_tramite = (array) $result->grupo_tramite;
                $result->datos_turno = (array) $result->datos_turno;
                $result->datos_turno['campos'] = (array) $result->datos_turno['campos'];
                $result = (array) $result;
            }
        }
        return $this->processError(
            $validateResult,
            function () use ($result) {
                return $this->respuestaData([], (array) $result);
            },
            $error
        );
    }

    /**
     * Devuelve el listado de turnos
     *
     * @param $params
     * @return ValidateResultado|object
     */
    public function findAllPaginate($params, $onError)
    {
        $resultset = [];
        $response = [];
        $ventanilla = $this->ventanillaRepository->find($params['ventanilla']);
        $validateResultado = $this->turnoValidator->validarGetRecepcionados($params, $ventanilla);

        if (! $validateResultado->hasError()) {
            $turnoGetter = new TurnoGetter(
                $this->ventanillaRepository,
                $this->redisServices,
                $this->turnoRepository
            );

            $validateResultado = $turnoGetter->getAll($params, $ventanilla);

            if (! $validateResultado->hasError()) {
                $response = json_decode($validateResultado->getEntity());

                $resultset = [
                    'resultset' => [
                        'count' => $response->cantTurnos,
                        'offset' => $params['offset'],
                        'limit' => $params['limit']
                    ]
                ];
            }
        }

        return $this->processError(
            $validateResultado,
            function () use ($resultset, $response) {
                return $this->respuestaData($resultset, $this->toArray($response->turnos));
            },
            $onError
        );
    }

    /**
     * Quita el primer elemento de la cola y lo retorna
     *
     * @param $params
     * @param $onError
     * @return mixed
     */
    public function getProximoTurno($params, $onError)
    {
        $result = [];
        $ventanilla = $this->ventanillaRepository->find($params['ventanilla']);
        $validateResultado = $this->turnoValidator->validarGetRecepcionados($params, $ventanilla);

        if (! $validateResultado->hasError()) {
            $turnoGetter = new TurnoGetter(
                $this->ventanillaRepository,
                $this->redisServices,
                $this->turnoRepository
            );

            $turno = $turnoGetter->getProximoTurno($params['puntoatencion'], $ventanilla);

            $result = [
                'id' => $turno->getId(),
                'tramite' => $turno->getTramite(),
                'puntoAtencion' => $turno->getPuntoAtencion()->getId(),
                'codigo' => $turno->getCodigo(),
                'fecha' => $turno->getFecha(),
                'hora' => $turno->getHora(),
                'estado' => $turno->getEstado(),
                'datosTurno' => [
                    'nombre' => $turno->getDatosTurno()->getNombre(),
                    'apellido' => $turno->getDatosTurno()->getApellido(),
                    'cuil' => $turno->getDatosTurno()->getCuil(),
                    'email' => $turno->getDatosTurno()->getEmail(),
                    'telefono' => $turno->getDatosTurno()->getTelefono(),
                    'campos' => $turno->getDatosTurno()->getCampos()
                ]
            ];
        }

        return $this->processError(
            $validateResultado,
            function () use ($result) {
                return $this->respuestaData([], $result);
            },
            $onError
        );
    }

    /**
     * Obtiene la posicion de un turno
     *
     * @param $id
     * @param $onError
     * @return mixed
     */
    public function getPosicionTurno($id, $onError)
    {
        $result = [];
        $turno = $this->turnoRepository->find($id);

        $validateResultado = $this->turnoValidator->validarTurno($turno);

        if (! $validateResultado->hasError()) {
            $cola = $this->colaRepository->findOneBy(['grupoTramiteSNTId' => $turno->getGrupoTramiteIdSNT()]);
            $pos = $this->redisServices->getPosicion($turno, $cola);

            if ($pos == -1) {
                $error['Turno'] = 'Turno no encontrado en la cola';
                $validateResultado = new ValidateResultado(null, $error);
            }

            $result = [
                'id' => $turno->getId(),
                'tramite' => $turno->getTramite(),
                'codigo' => ServicesHelper::obtenerCodigoSimple($turno->getCodigo()),
                'posicion' => $pos
            ];
        }

        return $this->processError(
            $validateResultado,
            function () use ($result) {
                return $this->respuestaData([], $result);
            },
            $onError
        );
    }
}
