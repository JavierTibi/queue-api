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
     * @ORM\Column(name="datos_turno_id", type="integer", nullable=true, unique=true)
     */
    private $datosTurnoId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_agente_id", type="integer", nullable=true)
     */
    private $userAgenteId;

    /**
     * @var int
     *
     * @ORM\Column(name="ventanilla_id", type="integer", nullable=true)
     */
    private $ventanillaId;

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
     * @ORM\Column(name="tramite", type="string", length=255, nullable=true)
     */
    private $tramite;

    /**
     * @var int
     *
     * @ORM\Column(name="cuil", type="bigint")
     */
    private $cuil;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=64)
     */
    private $codigo;


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
     * Set userAgenteId
     *
     * @param integer $userAgenteId
     *
     * @return Turno
     */
    public function setUserAgenteId($userAgenteId)
    {
        $this->userAgenteId = $userAgenteId;

        return $this;
    }

    /**
     * Get userAgenteId
     *
     * @return int
     */
    public function getUserAgenteId()
    {
        return $this->userAgenteId;
    }

    /**
     * Set ventanillaId
     *
     * @param integer $ventanillaId
     *
     * @return Turno
     */
    public function setVentanillaId($ventanillaId)
    {
        $this->ventanillaId = $ventanillaId;

        return $this;
    }

    /**
     * Get ventanillaId
     *
     * @return int
     */
    public function getVentanillaId()
    {
        return $this->ventanillaId;
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
     * Set horario
     *
     * @param \DateTime $horario
     *
     * @return Turno
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return \DateTime
     */
    public function getHorario()
    {
        return $this->horario;
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
     * Set horaEstado
     *
     * @param \DateTime $horaEstado
     *
     * @return Turno
     */
    public function setHoraEstado($horaEstado)
    {
        $this->horaEstado = $horaEstado;

        return $this;
    }

    /**
     * Get horaEstado
     *
     * @return \DateTime
     */
    public function getHoraEstado()
    {
        return $this->horaEstado;
    }

    /**
     * Set tramite
     *
     * @param string $tramite
     *
     * @return Turno
     */
    public function setTramite($tramite)
    {
        $this->tramite = $tramite;

        return $this;
    }

    /**
     * Get tramite
     *
     * @return string
     */
    public function getTramite()
    {
        return $this->tramite;
    }

    /**
     * Set cuil
     *
     * @param integer $cuil
     *
     * @return Turno
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
}

