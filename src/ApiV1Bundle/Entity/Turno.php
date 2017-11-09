<?php

namespace ApiV1Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Turno
 *
 * @ORM\Table(name="turno")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\TurnoRepository")
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
     * @var int
     *
     * @ORM\Column(name="punto_atencion_id", type="integer", nullable=true)
     */
    private $puntoAtencionId;

    /**
     * @var int
     *
     * @ORM\Column(name="tramite_id", type="integer", nullable=true)
     */
    private $tramiteId;

    /**
     * @var int
     *
     * @ORM\Column(name="grupo_tramite_id", type="integer", nullable=true)
     */
    private $grupoTramiteId;

    /**
     * @var int
     *
     * @ORM\Column(name="datos_turno_id", type="integer", nullable=true)
     */
    private $datosTurnoId;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=64)
     */
    private $codigo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora", type="time")
     */
    private $hora;

    /**
     * @var int
     *
     * @ORM\Column(name="estado", type="smallint")
     */
    private $estado;

    /**
     * @var int
     *
     * @ORM\Column(name="alerta", type="smallint", nullable=true)
     */
    private $alerta;

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
     * Set puntoAtencionId
     *
     * @param integer $puntoAtencionId
     *
     * @return Turno
     */
    public function setPuntoAtencionId($puntoAtencionId)
    {
        $this->puntoAtencionId = $puntoAtencionId;

        return $this;
    }

    /**
     * Get puntoAtencionId
     *
     * @return int
     */
    public function getPuntoAtencionId()
    {
        return $this->puntoAtencionId;
    }

    /**
     * Set tramiteId
     *
     * @param integer $tramiteId
     *
     * @return Turno
     */
    public function setTramiteId($tramiteId)
    {
        $this->tramiteId = $tramiteId;

        return $this;
    }

    /**
     * Get tramiteId
     *
     * @return int
     */
    public function getTramiteId()
    {
        return $this->tramiteId;
    }

    /**
     * Set grupoTramiteId
     *
     * @param integer $grupoTramiteId
     *
     * @return Turno
     */
    public function setGrupoTramiteId($grupoTramiteId)
    {
        $this->grupoTramiteId = $grupoTramiteId;

        return $this;
    }

    /**
     * Get grupoTramiteId
     *
     * @return int
     */
    public function getGrupoTramiteId()
    {
        return $this->grupoTramiteId;
    }

    /**
     * Set datosTurnoId
     *
     * @param integer $datosTurnoId
     *
     * @return Turno
     */
    public function setDatosTurnoId($datosTurnoId)
    {
        $this->datosTurnoId = $datosTurnoId;

        return $this;
    }

    /**
     * Get datosTurnoId
     *
     * @return int
     */
    public function getDatosTurnoId()
    {
        return $this->datosTurnoId;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Turno
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Turno
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return Turno
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     *
     * @return Turno
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return int
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set alerta
     *
     * @param integer $alerta
     *
     * @return Turno
     */
    public function setAlerta($alerta)
    {
        $this->alerta = $alerta;

        return $this;
    }

    /**
     * Get alerta
     *
     * @return int
     */
    public function getAlerta()
    {
        return $this->alerta;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     *
     * @return Turno
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
     * @return Turno
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
     * @return Turno
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

