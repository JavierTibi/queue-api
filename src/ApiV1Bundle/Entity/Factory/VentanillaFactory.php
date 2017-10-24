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

    public function __construct(VentanillaValidator $ventanillaValidator)
    {
        $this->ventanillaValidator = $ventanillaValidator;
    }
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function create($params)
    {
        $validateResultado = $this->ventanillaValidator->validarParams($params);

        if (! $validateResultado->hasError()) {
            $ventanilla = new Ventanilla($params['identificador']);

            return new ValidateResultado($ventanilla, []);
        }

        return $validateResultado;
    }
}