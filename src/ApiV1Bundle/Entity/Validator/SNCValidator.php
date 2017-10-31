<?php
/**
 * Created by PhpStorm.
 * User: jtibi
 * Date: 19/10/2017
 * Time: 3:00 PM
 */

namespace ApiV1Bundle\Entity\Validator;


use ApiV1Bundle\Helper\ServicesHelper;

class SNCValidator
{
    const CAMPO_REQUERIDO = 'Es un campo requerido';
    const CAMPO_NO_EXISTE = 'El campo no existe para poder ser validado';
    const NUMERICO = 'Debe ser un valor numérico';
    const MATRIZ = 'Debe ser del tipo array.';
    const EMAIL = 'Debe ser una dirección de mail valida';
    const FECHA = 'Debe ser una fecha valida';
    const HORA = 'Debe ser una hora valida';
    const CUIL = 'Debe ser un cuil válido';
    const JSON = 'Debe ser un objeto JSON valido';

    /**
     * Validar campos según las reglas
     *
     * @param array $campos
     * @param array $reglas
     * @return array
     */
    public function validar($campos, $reglas)
    {
        $errores = [];
        foreach ($reglas as $key => $regla) {
            $validaciones = $this->getValidaciones($regla);
            // valido si el campo existe
            if (array_key_exists($key, $campos)) {
                $validacion = $this->validarReglas($validaciones, $campos, $key);
                if (count($validacion)) {
                    $errores[ucfirst($key)] = $validacion;
                }
            } else {
                // es no requerido, lo tenemos que validar
                if (in_array('required', $validaciones)) {
                    $errores[ucfirst($key)] = self::CAMPO_NO_EXISTE;
                }
            }
        }
        return $errores;
    }


    /**
     * Obtener las reglas de validación de un campo
     *
     * @param string $regla
     * @return array
     */
    private function getValidaciones($regla)
    {
        return explode(':', $regla);
    }

    /**
     * Validar reglas
     *
     * @param array $validaciones
     * @param mixed $valor
     * @return array
     */
    private function validarReglas($validaciones, $campos, $key)
    {
        $errores = [];
        foreach ($validaciones as $validacion) {
            $error = $this->{trim($validacion)}($campos, $key);
            if ($error) {
                return $error;
            }
        }
        return $errores;
    }

    /**
     * Validar si es requerido
     *
     * @param mixed $valor
     * @return string|NULL
     */
    private function required($campos, $key)
    {
        if (! isset($campos[$key]) || empty($campos[$key])) {
            return self::CAMPO_REQUERIDO;
        }
        return null;
    }

    /**
     * Validar si es un integro
     *
     * @param mixed $var
     * @return string|NULL
     */
    private function integer($campos, $key)
    {
        $isInt = (bool) filter_var($campos[$key], FILTER_VALIDATE_INT) || (string) $campos[$key] === '0';
        if (! $isInt) {
            return self::NUMERICO;
        }
        return null;
    }

    /**
     * Validar si es numérico
     *
     * @param mixed $var
     * @return string|NULL
     */
    private function numeric($campos, $key)
    {
        if (! is_numeric($campos[$key])) {
            return self::NUMERICO;
        }
        return null;
    }

    /**
     * Validar si es un email
     *
     * @param string $var
     * @return string|NULL
     */
    private function email($campos, $key)
    {
        if (! filter_var($campos[$key], FILTER_VALIDATE_EMAIL)) {
            return self::EMAIL;
        }
        return null;
    }

    /**
     * Validar si es un float
     *
     * @param mixed $var
     * @return string|NULL
     */
    private function float($campos, $key)
    {

        if (! filter_var($campos[$key], FILTER_VALIDATE_FLOAT)) {
            return self::NUMERICO;
        }
        return null;
    }

    /**
     * Validar si es una fecha
     *
     * @param mixed $date
     * @return string|NULL
     */
    private function date($campos, $key)
    {
        $format = 'Y-m-d';
        $date = $campos[$key];

        try {
            $d = new \DateTime(trim($date));
        } catch (\Exception $e) {
            return self::FECHA;
        }
        if (! ($d && $d->format($format) == trim($date))) {
            return self::FECHA;
        }
        return null;
    }

    /**
     * Validar si es fecha con zona horaria
     *
     * @param string $date
     * @return string|NULL
     */
    private function dateTZ($campos, $key)
    {
        $date = $campos[$key];
        $d = new \DateTime(trim($date));
        if (! ($d && $this->formatDateTZ($d) == trim($date))) {
            return self::FECHA;
        }
        return null;
    }

    /**
     * Validar si es una hora valida
     *
     * @param mixed $time
     * @return string|NULL
     */
    private function time($campos, $key)
    {
        $format = 'H:i';
        $time = $campos[$key];
        $d = new \DateTime(trim($time));
        if (! ($d && $d->format($format) == trim($time))) {
            return self::HORA;
        }
        return null;
    }

    /**
     * Formato de fecha con timezone
     *
     * @param \Datetime $date
     * @return string
     */
    private function formatDateTZ($date)
    {
        return $date->format('Y-m-d\TH:i:s') . '.' . substr($date->format('u'), 0, 3) . 'Z';
    }

    /**
     * Validar si el texto es JSON
     *
     * @param mixed $var
     * @return number|NULL
     */
    private function json($campos, $key)
    {
        // this is probably a JSON object already decoded
        if (is_array($campos[$key])) {
            return null;
        }
        if (is_string($campos[$key]) && is_null(json_decode($campos[$key]))) {
            return self::JSON;
        }
        return null;
    }

    /**
     * Validar formato de CUIL
     *
     * @param $cuil
     * @return string|NULL
     */
    private function cuil($campos, $key)
    {
        if (preg_match("/^\d{2}\-\d{8}\-\d{1}$/", $campos[$key]) == 0) {
            return self::CUIL;
        }
        return null;
    }

    /**
     * Validar si es un array
     *
     * @param array $var
     * @return string|NULL
     */
    private function matriz($campos, $key)
    {
        if (! ServicesHelper::toArray($campos[$key])) {
            return self::MATRIZ;
        }
        return null;
    }

    public function validarAgente($agente)
    {
        $errors = [];
        if (! $agente) {
            $errors['agente'] = 'Agente inexistente';
        }
        return new ValidateResultado(null, $errors);
    }

    public function validarVentanilla($ventanilla)
    {
        $errors = [];
        if (! $ventanilla) {
            $errors['Ventanilla'] = "Ventanilla inexistente.";
        }
        return new ValidateResultado(null, $errors);
    }
}

