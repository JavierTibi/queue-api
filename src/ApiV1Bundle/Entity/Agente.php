<?php

namespace ApiV1Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Agente
 *
 * @ORM\Table(name="agente")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\AgenteRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
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
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="ventanilla_id", type="integer")
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
     * @param $user
     * @param $puntoAtencion
     */
    public function __construct($nombre, $apellido, $user, $puntoAtencion)
    {
        parent::__construct($nombre, $apellido, $user, $puntoAtencion);
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
     * Get ventanillaId
     *
     * @return int
     */
    public function getVentanillaActual()
    {
        return $this->ventanillaActual;
    }

    /**
     * @param int $ventanillaActual
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
}

