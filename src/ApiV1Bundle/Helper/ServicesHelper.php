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

}
