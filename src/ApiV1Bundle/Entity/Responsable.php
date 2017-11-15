<?php
namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiV1Bundle\Entity\User;

/**
 * Responsable
 *
 * @ORM\Table(name="user_responsable")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\ResponsableRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
 * @ORM\HasLifecycleCallbacks()
 */
class Responsable extends Usuario
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="PuntoAtencion")
     * @ORM\JoinColumn(name="punto_atencion_id", referencedColumnName="id")
     */
    protected $puntoAtencion;

    /**
     * Fecha de creación del agente
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_creado", type="datetimetz")
     */
    private $fechaCreado;

    /**
     * Fecha de modificación del agente
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_modificado", type="datetimetz")
     */
    private $fechaModificado;

    /**
     * Fecha de borrado del agente
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_borrado", type="datetimetz", nullable=true)
     */
    private $fechaBorrado;

    /**
     * Responsable constructor.
     * @param $nombre
     * @param $apellido
     * @param $puntoAtencion
     * @param User $user
     */
    public function __construct($nombre, $apellido, $puntoAtencion, User $user)
    {
        parent::__construct($nombre, $apellido, $user);
        $this->puntoAtencion = $puntoAtencion;
    }

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
     * @return PuntoAtencion
     */
    public function getPuntoAtencion()
    {
        return $this->puntoAtencion;
    }

    /**
     * @return int
     */
    public function getPuntoAtencionId()
    {
        return $this->getPuntoAtencion()->getId();
    }

    /**
     * Genera las fechas de creación y modificación
     *
     * @ORM\PrePersist
     */
    public function setFechas()
    {
        $this->fechaCreado = new \DateTime();
        $this->fechaModificado = new \DateTime();
    }

    /**
     * Actualiza la fecha de modificación
     *
     * @ORM\PreUpdate
     */
    public function updatedFechas()
    {
        $this->fechaModificado = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getFechaBorrado()
    {
        return $this->fechaBorrado;
    }
    
    /**
     * 
     * @param type $puntoAtencionID
     */
    public function setPuntoAtencion($puntoAtencionID) {
        $this->puntoAtencion = $puntoAtencionID;
    }

    /**
     * @return string
     */
    public function getNombrePuntoAtencion()
    {
        return $this->getPuntoAtencion()->getNombre();
    }
}
