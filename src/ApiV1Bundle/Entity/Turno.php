<?php

namespace ApiV1Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\TurnoRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
 * @ORM\HasLifecycleCallbacks()
 */
class Turno
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
     * @var PuntoAtencion
     *
     * @ORM\ManyToOne(targetEntity="PuntoAtencion")
     * @ORM\JoinColumn(name="punto_atencion_id", referencedColumnName="id")
     */
    private $puntoAtencion;

    /**
     * @var int
     * @ORM\Column(name="grupo_tramite_snt_id")
     */
    private $grupoTramiteIdSNT;

    /**
     * Campo de relación con los datos del turno
     * A un turno le corresponde un solo grupo de datos
     *
     * @var DatosTurno
     * @ORM\OneToOne(targetEntity="DatosTurno", inversedBy="turno", cascade={"persist"})
     * @ORM\JoinColumn(name="datos_turno_id", referencedColumnName="id")
     */
    private $datosTurno;

    /**
     * @var Agente
     *
     * @ORM\ManyToOne(targetEntity="Agente")
     * @ORM\JoinColumn(name="user_agente_id", referencedColumnName="id")
     */
    private $agente;

    /**
     * @var Ventanilla
     *
     * @ORM\ManyToOne(targetEntity="Ventanilla")
     * @ORM\JoinColumn(name="ventanilla_id", referencedColumnName="id")
     */
    private $ventanilla;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horario", type="time")
     */
    private $horario;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="smallint")
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_estado", type="time")
     */
    private $horaEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="tramite", type="string", length=255, nullable=false)
     */
    private $tramite;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=64)
     */
    private $codigo;

    /**
     * Fecha de creación
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_creado", type="datetime")
     */
    private $fechaCreado;

    /**
     * Fecha de modificación
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_modificado", type="datetime")
     */
    private $fechaModificado;

    /**
     * Fecha de borrado
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_borrado", type="datetimetz", nullable=true)
     */
    private $fechaBorrado;

    /**
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
     * @return DatosTurno
     */
    public function getDatosTurno()
    {
        return $this->datosTurno;
    }

    /**
     * @return Agente
     */
    public function getAgente()
    {
        return $this->agente;
    }

    /**
     * @return Ventanilla
     */
    public function getVentanilla()
    {
        return $this->ventanilla;
    }

    /**
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @return \DateTime
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @return \DateTime
     */
    public function getHoraEstado()
    {
        return $this->horaEstado;
    }

    /**
     * @return string
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Genera las fechas de creación y modificación del turno
     *
     * @ORM\PrePersist
     */
    public function setFechas()
    {
        $this->fechaCreado = new \DateTime();
        $this->fechaModificado = new \DateTime();
    }

    /**
     * Actualiza la fecha de modificación del turno
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

