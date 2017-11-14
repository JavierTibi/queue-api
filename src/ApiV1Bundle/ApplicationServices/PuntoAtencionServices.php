<?php
namespace ApiV1Bundle\ApplicationServices;

use ApiV1Bundle\Repository\PuntoAtencionRepository;
use Symfony\Component\DependencyInjection\Container;

class PuntoAtencionServices extends SNCServices
{
    private $puntoAtencionRepository;

    public function __construct(
        Container $container,
        PuntoAtencionRepository $puntoAtencionRepository
    )
    {
        parent::__construct($container);
        $this->puntoAtencionRepository = $puntoAtencionRepository;
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
                'count' => count($result),
                'offset' => $offset,
                'limit' => $limit
            ]
        ];
        return $this->respuestaData($resultset, $result);
    }
        
}