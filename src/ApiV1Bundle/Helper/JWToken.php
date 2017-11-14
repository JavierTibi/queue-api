<?php
/**
 * JWToken class
 * Docs: https://github.com/lcobucci/jwt/blob/3.2/README.md
 */
namespace ApiV1Bundle\Helper;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWToken
{
    private $secret;
    private $builder;
    private $parser;
    private $signer;
    private $validationData;
    private $isValid = false;
    private $roles = null;

    public function __construct($secret, Builder $builder, Parser $parser, ValidationData $validationData)
    {
        $this->secret = $secret;
        $this->token = $builder;
        $this->parser = $parser;
        $this->validationData = $validationData;
        $this->signer = new Sha256();
    }
    /**
     * Generar JWToken
     *
     * @return \Lcobucci\JWT\Builder
     */
    public function getToken($uid, $username, $role)
    {
        $token = $this->token;
        $token->setIssuer($this->getDomain());
        $token->setAudience($this->getDomain());
        $token->setIssuedAt(time());
        $token->setExpiration(time() + 14400);
        $token->setId(md5($this->secret . $this->getDomain()));
        $token->set('timestamp', time());
        $token->set('uid', $uid);
        $token->set('username', $username);
        $token->set('role', $role);
        $token->sign($this->signer, $this->secret);
        return (string) $token->getToken();
    }

    /**
     * Validar token
     *
     * @param $tokenString
     * @return boolean
     */
    public function validate($tokenString)
    {
        try {
            $token = $this->parseToken($tokenString);
            $isValid = $token->validate($this->validationData());
            if ($isValid) {
                // verify the token signature
                if ($token->verify($this->signer, $this->secret)) {
                    $this->isValid = $isValid;
                    $this->role = $token->getClaim('role');
                }
            }
        } catch (\Exception $e) {
            // do nothing
        }
        return $this;
    }

    /**
     * Is token valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Rol saved on the token
     *
     * @return string
     */
    public function getRol()
    {
        return $this->role;
    }

    /**
     * Parsear token
     *
     * @param $token
     * @return \Lcobucci\JWT\Token
     */
    private function parseToken($token)
    {
        return $this->parser->parse((string) $token);
    }

    /**
     * Datos para validar un token
     *
     * @return \Lcobucci\JWT\ValidationData
     */
    private function validationData()
    {
        $this->validationData->setIssuer($this->getDomain());
        $this->validationData->setAudience($this->getDomain());
        $this->validationData->setId(md5($this->secret . $this->getDomain()));
        return $this->validationData;
    }

    /**
     * Obtener el dominio que genera el token
     *
     * @return string
     */
    public function getDomain()
    {
        return gethostname();
    }
}
