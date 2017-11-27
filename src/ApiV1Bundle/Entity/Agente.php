<?php
namespace ApiV1Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agente
 *
 * @ORM\Table(name="user_agente")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\AgenteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Agente extends Usuario
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
    private $puntoAtencion;

    /**
     * @var Ventanilla
     * @ORM\OneToOne(targetEntity="Ventanilla")
     * @ORM\JoinColumn(name="ventanilla_id", referencedColumnName="id", nullable = true)
     */
    private $ventanillaActual;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Ventanilla", inversedBy="agentes")
     * @ORM\JoinTable(name="agente_ventanilla")
     **/
    private $ventanillas;

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
     * Agente constructor.
     * @param $nombre
     * @param $apellido
     * @param $puntoAtencion
     * @param User $user
     */
    public function __construct($nombre, $apellido, $puntoAtencion, User $user)
    {
        parent::__construct($nombre, $apellido, $user);
        $this->puntoAtencion = $puntoAtencion;
        $this->ventanillas = new ArrayCollection();
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
        return $this->getPuntoAtencion() ? $this->getPuntoAtencion()->getId() : null;
    }

    /**
     * Get ventanillaId
     *
     * @return Ventanilla
     */
    public function getVentanillaActual()
    {
        return $this->ventanillaActual;
    }

    /**
     * @param Ventanilla $ventanillaActual ¬1"¬°!°"
     */
    public function setVentanillaActual($ventanillaActual)
    {
        $this->ventanillaActual = $ventanillaActual;
    }

    /**
     * @return ArrayCollection
     */
    public function getVentanillas()
    {
        return $this->ventanillas;
    }

    /**
     * Agrega una ventanilla a un Agente
     * @param Ventanilla $ventanilla
     * @return ArrayCollection
     */
    public function addVentanilla(Ventanilla $ventanilla)
    {
        $this->ventanillas[] = $ventanilla;
        return $this->getVentanillas();
    }

    /**
     * Eliminar una ventanilla a un Agente
     * @param Ventanilla $ventanilla
     * @return ArrayCollection
     */
    public function removeVentanilla(Ventanilla $ventanilla)
    {
        $this->ventanillas->removeElement($ventanilla);
        return $this->getVentanillas();
    }

    /**
     * Eliminar todas las ventanillas
     */
    public function removeAllVentanilla()
    {
        $this->ventanillas->clear();
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
     * @param int $puntoAtencionID
     */
    public function setPuntoAtencion($puntoAtencionID)
    {
        $this->puntoAtencion = $puntoAtencionID;
    }

    /**
     * @return string
     */
    public function getNombrePuntoAtencion()
    {
        return $this->getPuntoAtencion() ? $this->getPuntoAtencion()->getNombre() : null;
    }

    /**
     * @return NULL|number
     */
    public function getVentanillaActualId()
    {
        return $this->getVentanillaActual() ? $this->getVentanillaActual()->getId() : null;
    }
}
