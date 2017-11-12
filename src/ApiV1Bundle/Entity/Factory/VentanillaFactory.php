<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 23/10/2017
 * Time: 10:52 AM
 */

namespace ApiV1Bundle\Entity\Factory;


use ApiV1Bundle\Entity\Validator\ValidateResultado;
use ApiV1Bundle\Entity\Validator\VentanillaValidator;
use ApiV1Bundle\Entity\Ventanilla;

class VentanillaFactory
{
    private $ventanillaValidator;
    private $colaRepository;
    private $puntoAtencionRepository;

    public function __construct(VentanillaValidator $ventanillaValidator, $colaRepository, $puntoAtencionRepository)
    {
        $this->ventanillaValidator = $ventanillaValidator;
        $this->colaRepository = $colaRepository;
        $this->puntoAtencionRepository = $puntoAtencionRepository;
    }
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $puntoAtencion = $this->puntoAtencionRepository->find($params['puntoAtencion']);
        $validateResultado = $this->ventanillaValidator->validarParams($params, $puntoAtencion);

        if (! $validateResultado->hasError()) {

            $ventanilla = new Ventanilla($params['identificador'], $puntoAtencion);

            foreach ($params['colas'] as $colaId) {
                $cola = $this->colaRepository->find($colaId);
                $ventanilla->addCola($cola);
            }

            return new ValidateResultado($ventanilla, []);
        }

        return $validateResultado;
    }
}