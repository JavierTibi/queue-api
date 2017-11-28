<?php

namespace ApiV1Bundle\Entity\Validator;

use ApiV1Bundle\Repository\ColaRepository;
use ApiV1Bundle\Entity\Ventanilla;

class VentanillaValidator extends SNCValidator
{
    private $colaRepository;

    public function __construct(ColaRepository $colaRepository)
    {
        $this->colaRepository= $colaRepository;
    }
    /**
     * @param $params
     * @return ValidateResultado
     */
    public function validarParams($params, $puntoAtencion) {
        $errors = $this->validar($params, [
            'puntoAtencion' => 'required',
            'identificador' => 'required',
            'colas' => 'required:matriz'
        ]);

        foreach ($params['colas'] as $idCola) {
                $cola = $this->colaRepository->find($idCola);
                $validateCola = $this->validarCola($cola);
                if ($validateCola->hasError()) {
                    return $validateCola;
                }
            }
            
            if (! count($errors) > 0) {
                return $this->validarPuntoAtencion($puntoAtencion);
            }

        return new ValidateResultado(null, $errors);
    }

    /**
     * Validación del endpoint edit Ventanilla
     * @param $ventanilla
     * @param $params
     * @return ValidateResultado
     */
    public function validarEdit($ventanilla, $params) {
        $validateResultado = $this->validarVentanilla($ventanilla);

        if (! $validateResultado->hasError()) {
            $errors = $this->validar($params, [
                'identificador' => 'required',
                'colas' => 'required:matriz'
            ]);

            return new ValidateResultado(null, $errors);
        }

        return $validateResultado;
    }
}
