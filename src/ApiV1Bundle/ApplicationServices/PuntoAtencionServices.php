<?php
namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Entity\Factory\PuntoAtencionFactory;
use ApiV1Bundle\Repository\PuntoAtencionRepository;
use ApiV1Bundle\Entity\Validator\PuntoAtencionValidator;
use Symfony\Component\DependencyInjection\Container;
use ApiV1Bundle\Entity\Sync\PuntoAtencionSync;

class PuntoAtencionServices extends SNCServices
{
    private $puntoAtencionRepository;
    private $puntoAtencionValidator;
    
    /**
     * PuntoAtencionServices construct
     * @param Container $container
     * @param PuntoAtencionRepository $puntoAtencionRepository
     * @param PuntoAtencionValidator $puntoAtencionValidator
     */
    public function __construct(
        Container $container,
        PuntoAtencionRepository $puntoAtencionRepository,
        PuntoAtencionValidator $puntoAtencionValidator
    ) 
    {
        parent::__construct($container);
        $this->puntoAtencionRepository = $puntoAtencionRepository;
        $this->puntoAtencionValidator = $puntoAtencionValidator;
    }

    /**
     * @param $limit
     * @param $offset
     * @return object
     */
    public function findAllPaginate($limit, $offset)
    {
        $result = $this->puntoAtencionRepository->findAllPaginate($offset, $limit);
        $resultset = [
            'resultset' => [
                'count' => $this->puntoAtencionRepository->getTotal(),
                'offset' => $offset,
                'limit' => $limit
            ]
        ];
        return $this->respuestaData($resultset, $result);
    }

    /**
     * Crear punto de atencion
     * 
     * @param type $params Array con los datos para modificar un punto de atención
     * @param $success | Si tuvo éxito o no
     * @param string $error Mensaje de error al modificar un punto de atención
     * @return type
     */
    public function create($params, $sucess, $error) {
        $puntoAtencionFactory = new PuntoAtencionFactory(
            $this->puntoAtencionRepository,
            $this->puntoAtencionValidator
        );
        $validateResult = $puntoAtencionFactory->create($params);
        
        return $this->processResult(
            $validateResult, 
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->puntoAtencionRepository->save($entity));
            },
            $error
            );
    }
    
    /**
     * Editar punto de atencion 
     * 
     * @param integer $id Identificador único de un punto de atención
     * @param type $params Array con los datos para modificar un punto de atención
     * @param $success | Si tuvo éxito o no
     * @param string $error Mensaje de error al modificar un punto de atención
     * @return type
     */
    public function edit($id, $params, $sucess, $error) {
        $puntoAtencionSync = new PuntoAtencionSync(
            $this->puntoAtencionRepository,
            $this->puntoAtencionValidator
        );
        $validateResult = $puntoAtencionSync->edit($id, $params);
        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->puntoAtencionRepository->flush());
            },
            $error
        );
    }
    
    /**
     * Eliminar punto de atencion 
     * 
     * @param integer $id Identificador único de un punto de atención
     * @param $success  Si tuvo éxito o no
     * @param string $error Mensaje de error al modificar un punto de atención
     * @return type
     */
    public function delete($id, $sucess, $error) {
        $puntoAtencionSync = new PuntoAtencionSync(
            $this->puntoAtencionRepository,
            $this->puntoAtencionValidator
        );
        $validateResult = $puntoAtencionSync->delete($id);
        return $this->processResult($validateResult,
            function($entity) use ($sucess){
                return call_user_func($sucess, $this->puntoAtencionRepository->remove($entity));
            },
                $error);
    }
}
