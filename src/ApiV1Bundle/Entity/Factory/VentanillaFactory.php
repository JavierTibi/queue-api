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

    public function __construct(VentanillaValidator $ventanillaValidator, $colaRepository)
    {
        $this->ventanillaValidator = $ventanillaValidator;
        $this->colaRepository = $colaRepository;
    }
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $validateResultado = $this->ventanillaValidator->validarParams($params);

        if (! $validateResultado->hasError()) {
            //TODO find punto de atencion
            $ventanilla = new Ventanilla($params['identificador'], $params['puntoAtencion']);

            foreach ($params['colas'] as $colaId) {
                $cola = $this->colaRepository->find($colaId);
                $ventanilla->addCola($cola);
            }

            return new ValidateResultado($ventanilla, []);
        }

        return $validateResultado;
    }
}