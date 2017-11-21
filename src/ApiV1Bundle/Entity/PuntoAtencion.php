<?php

namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PuntoAtencion
 *
 * @ORM\Table(name="punto_atencion")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\PuntoAtencionRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
 * @ORM\HasLifecycleCallbacks()
 */
class PuntoAtencion
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
     * @ORM\Column(name="punto_atencion_id_snt", type="integer", unique=true)
     */
    private $puntoAtencionIdSnt;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

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
     * Set puntoAtencionIdSnt
     *
     * @param integer $puntoAtencionIdSnt
     *
     * @return PuntoAtencion
     */
    public function setPuntoAtencionIdSnt($puntoAtencionIdSnt)
    {
        $this->puntoAtencionIdSnt = $puntoAtencionIdSnt;

        return $this;
    }

    /**
     * Get puntoAtencionIdSnt
     *
     * @return int
     */
    public function getPuntoAtencionIdSnt()
    {
        return $this->puntoAtencionIdSnt;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return PuntoAtencion
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
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     *
     * @return PuntoAtencion
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
     * @return PuntoAtencion
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
     * @return PuntoAtencion
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
    
    /**
     * Genera las fechas de creación y modificación de un punto de atención
     *
     * @ORM\PrePersist
     */
    public function setFechas()
    {
        $this->fechaCreado = new \DateTime();
        $this->fechaModificado = new \DateTime();
    }
}

