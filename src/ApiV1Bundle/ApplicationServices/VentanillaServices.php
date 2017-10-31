<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 23/10/2017
 * Time: 10:51 AM
 */

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Factory\VentanillaFactory;
use ApiV1Bundle\Entity\Sync\VentanillaSync;
use ApiV1Bundle\Entity\Validator\VentanillaValidator;
use ApiV1Bundle\Entity\Ventanilla;
use ApiV1Bundle\Repository\ColaRepository;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

class VentanillaServices extends SNCServices
{
    private $ventanillaRepository;
    private $ventanillaValidator;
    private $colaRepository;

    public function __construct(
        Container $container,
        VentanillaRepository $ventanillaRepository,
        VentanillaValidator $ventanillaValidator,
        ColaRepository $colaRepository
    )
    {
        parent::__construct($container);
        $this->ventanillaRepository = $ventanillaRepository;
        $this->ventanillaValidator = $ventanillaValidator;
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
        $ventanillaFactory = new VentanillaFactory(
            $this->ventanillaValidator,
            $this->colaRepository
        );

        $validateResult = $ventanillaFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->ventanillaRepository->save($entity));
            },
            $error
        );
    }

    /**
     * Editar una ventanilla
     *
     * @param $params
     * @param $id
     * @param $success
     * @param $error
     * @return mixed
     */
    public function edit($params, $id, $success, $error)
    {
        $ventanillaSync = new VentanillaSync(
            $this->ventanillaValidator,
            $this->ventanillaRepository,
            $this->colaRepository
        );

        $validateResult = $ventanillaSync->edit($id, $params);

        return $this->processResult(
            $validateResult,
            function () use ($success) {
                return call_user_func($success, $this->ventanillaRepository->flush());
            },
            $error
        );
    }

    /**
     * Elimina una ventanilla
     *
     * @param integer $id Identificador único
     * @param $success | Indica si tuvo éxito o no
     * @param string $error Mensaje con el error ocurrido al eliminar
     * @return mixed
     */
    public function delete($id, $success, $error)
    {
        $ventanillaSync = new VentanillaSync(
            $this->ventanillaValidator,
            $this->ventanillaRepository,
            $this->colaRepository
        );

        $validateResult = $ventanillaSync->delete($id);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($success) {
                return call_user_func($success, $this->ventanillaRepository->remove($entity));
            },
            $error
        );
    }

    /**
     * @param $puntoAtencionId
     * @param $limit
     * @param $offset
     * @return object
     */
    public function findAllPaginate($puntoAtencionId, $limit, $offset)
    {
        $result = $this->ventanillaRepository->findAllPaginate($puntoAtencionId, $offset, $limit);
        $resultset = [
            'resultset' => [
                'count' => count($result),
                'offset' => $offset,
                'limit' => $limit
            ]
        ];
        return $this->respuestaData($resultset, $result);
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        $ventanilla = $this->ventanillaRepository->find($id);
        $validateResultado = $this->ventanillaValidator->validarVentanilla($ventanilla);

        if (! $validateResultado->hasError()) {
            return $this->respuestaData([], $ventanilla);
        }

        return $this->respuestaData([], null);
    }
}