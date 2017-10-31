<?php
namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 *
 * @ORM\MappedSuperclass
 */
abstract class Usuario
{

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=128)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="apellido", type="string", length=128)
     */
    protected $apellido;

    /**
     * @var int
     * //TODO cambiar la relacion con punto de atencion
     * @ORM\Column(name="punto_atencion_id", type="integer")
     */
    protected $puntoAtencion;

    /**
     * Un usuario tiene un User
     * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    protected function __construct($nombre, $apellido, $user, $puntoAtencion)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->user = $user;
        $this->puntoAtencion = $puntoAtencion;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    protected function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    protected function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Get puntoAtencionId
     *
     * @return int
     */
    protected function getPuntoAtencion()
    {
        return $this->puntoAtencion;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @param string $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * @param int $puntoAtencion
     */
    public function setPuntoAtencion($puntoAtencion)
    {
        $this->puntoAtencion = $puntoAtencion;
    }

}
