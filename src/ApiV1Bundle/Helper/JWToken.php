<?php
/**
 * JWToken class
 * Docs: https://github.com/lcobucci/jwt/blob/3.2/README.md
 */
namespace ApiV1Bundle\Helper;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;

class JWToken
{
    public function __construct()
    {
        $this->domain = $_SERVER['SERVER_NAME'];
    }

    public function token()
    {
        $token = new Builder();
        $token->setIssuer($this->domain);
        $token->setAudience($this->domain);
        $token->setId(); // the jti claim
        return $token;
    }
}
