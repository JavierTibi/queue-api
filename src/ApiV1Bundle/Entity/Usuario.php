<?php
namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Usuario
 * @ORM\MappedSuperclass
 */

/**
 * Class Usuario
 * @package ApiV1Bundle\Entity
 * /**
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\UsuarioRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"agente" = "Agente", "responsable" = "Responsable", "admin" = "Admin"})
 */

abstract class Usuario
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
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
     * Un usuario tiene un User
     * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    protected function __construct($nombre, $apellido, $user)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->user = $user;
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
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
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

    abstract public function getPuntoAtencionId();

    abstract public function getNombrePuntoAtencion();

}
