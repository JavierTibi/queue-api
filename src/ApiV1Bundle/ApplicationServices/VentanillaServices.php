<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 23/10/2017
 * Time: 10:51 AM
 */

namespace ApiV1Bundle\ApplicationServices;


use ApiV1Bundle\Entity\Factory\VentanillaFactory;
use ApiV1Bundle\Entity\Validator\VentanillaValidator;
use ApiV1Bundle\Entity\Ventanilla;
use ApiV1Bundle\Repository\VentanillaRepository;
use Symfony\Component\DependencyInjection\Container;

class VentanillaServices extends SNCServices
{
    private $ventanillaRepository;
    private $ventanillaValidator;

    public function __construct(
        Container $container,
        VentanillaRepository $ventanillaRepository,
        VentanillaValidator $ventanillaValidator
    )
    {
        parent::__construct($container);
        $this->ventanillaRepository = $ventanillaRepository;
        $this->ventanillaValidator = $ventanillaValidator;
    }

    /**
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        $ventanillaFactory = new VentanillaFactory($this->ventanillaValidator);

        $validateResult = $ventanillaFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->ventanillaRepository->save($entity));
            },
            $error
        );
    }
}