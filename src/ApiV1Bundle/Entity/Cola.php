<?php

namespace ApiV1Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cola
 *
 * @ORM\Table(name="cola")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\ColaRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
 * @ORM\HasLifecycleCallbacks()
 */
class Cola
{

    const TIPO_GRUPO_TRAMITE = 1;
    const TIPO_POSTA = 2;

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
     * @ORM\ManyToOne(targetEntity="PuntoAtencion")
     * @ORM\JoinColumn(name="punto_atencion_id", referencedColumnName="id")
     */
    private $puntoAtencion;

    /**
     * @var string
     * @Assert\NotNull(
     *     message="El campo Nombre no puede estar vacío."
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Este campo Nombre solo acepta caracteres alfanumerico."
     * )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     * @Assert\NotNull(
     *     message="El campo Número no puede estar vacío."
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Este campo Identificador solo acepta caracteres alfanumerico."
     * )
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var int
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;

    /**
     * Colección de ventanillas que tiene una cola
     * Cola puede pertenecer a N ventanillas
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Ventanilla", mappedBy="colas")
     */
    private $ventanillas;

    public function __construct($nombre, $numero, $puntoAtencion, $tipo)
    {
        $this->nombre = $nombre;
        $this->puntoAtencion = $puntoAtencion;
        $this->tipo = $tipo;
        $this->ventanillas = new ArrayCollection();
    }

    /**
     * Fecha de creación de la ventanilla
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_creado", type="datetimetz")
     */
    private $fechaCreado;

    /**
     * Fecha de modificación de la ventanilla
     *
     * @var \DateTime
     * @ORM\Column(name="fecha_modificado", type="datetimetz")
     */
    private $fechaModificado;

    /**
     * Fecha de borrado de la ventanilla
     *
     * @var \DateTime
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
     * Get puntoatencionId
     *
     * @return int
     */
    public function getPuntoAtencion()
    {
        return $this->puntoAtencion;
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
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

}

