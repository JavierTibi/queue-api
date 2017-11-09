<?php

namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosTurno
 *
 * @ORM\Table(name="datos_turno")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\DatosTurnoRepository")
 */
class DatosTurno
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=80)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=80)
     */
    private $apellido;

    /**
     * @var int
     *
     * @ORM\Column(name="cuil", type="bigint")
     */
    private $cuil;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var array
     *
     * @ORM\Column(name="campos", type="json_array")
     */
    private $campos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creado", type="datetimetz")
     */
    private $fechaCreado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificado", type="datetimetz")
     */
    private $fechaModificado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_borrado", type="datetimetz", nullable=true)
     */
    private $fechaBorrado;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return DatosTurno
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return DatosTurno
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set cuil
     *
     * @param integer $cuil
     *
     * @return DatosTurno
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;

        return $this;
    }

    /**
     * Get cuil
     *
     * @return int
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return DatosTurno
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return DatosTurno
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set campos
     *
     * @param array $campos
     *
     * @return DatosTurno
     */
    public function setCampos($campos)
    {
        $this->campos = $campos;

        return $this;
    }

    /**
     * Get campos
     *
     * @return array
     */
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     *
     * @return DatosTurno
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set fechaModificado
     *
     * @param \DateTime $fechaModificado
     *
     * @return DatosTurno
     */
    public function setFechaModificado($fechaModificado)
    {
        $this->fechaModificado = $fechaModificado;

        return $this;
    }

    /**
     * Get fechaModificado
     *
     * @return \DateTime
     */
    public function getFechaModificado()
    {
        return $this->fechaModificado;
    }

    /**
     * Set fechaBorrado
     *
     * @param \DateTime $fechaBorrado
     *
     * @return DatosTurno
     */
    public function setFechaBorrado($fechaBorrado)
    {
        $this->fechaBorrado = $fechaBorrado;

        return $this;
    }

    /**
     * Get fechaBorrado
     *
     * @return \DateTime
     */
    public function getFechaBorrado()
    {
        return $this->fechaBorrado;
    }
}

