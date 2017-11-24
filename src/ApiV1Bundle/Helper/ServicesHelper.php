<?php
namespace ApiV1Bundle\Helper;

class ServicesHelper
{
    public static function toArray($data)
    {
        if (is_array($data)) {
            return $data;
        }
        if (json_decode($data)) {
            return json_decode($data, true);
        }
        return null;
    }
    
    /**
     * Pasa un código unico a sus primeros 8 caracters
     *
     * @param string $code
     * @return string
     */
    public static function obtenerCodigoSimple($code)
    {
        $parts = explode('-', $code);
        return $parts[0];
    }

}
