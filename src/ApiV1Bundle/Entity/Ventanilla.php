<?php
namespace ApiV1Bundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Ventanilla
 *
 * @ORM\Table(name="ventanilla")
 * @ORM\Entity(repositoryClass="ApiV1Bundle\Repository\VentanillaRepository")
 * @Gedmo\SoftDeleteable(fieldName="fechaBorrado")
 * @ORM\HasLifecycleCallbacks()
 */
class Ventanilla
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
     * Identificador alfanumerico de la ventanilla
     * @var string
     * @Assert\NotNull(
     *     message="El campo Identificador no puede estar vacío"
     * )
     * @Assert\Type(
     *     type="string",
     *     message="Este campo Identificador solo acepta caracteres alfanumerico."
     * )
     * @ORM\Column(name="identificador", type="string", length=10)
     */
    private $identificador;

    /**
     * Colección de colas que atiende una ventanilla
     * Ventanilla puede pertenecer a N colas
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Cola", mappedBy="ventanillas")
     */
    private $colas;

    /**
     * Colección de agentes que atiende una ventanilla
     * Ventanilla puede pertenecer a N agentes
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Agente", mappedBy="ventanillas")
     */
    private $agentes;

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

    public function __construct($identificador)
    {
        $this->identificador = $identificador;
        $this->agentes = new ArrayCollection();
        $this->colas = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getColas()
    {
        return $this->colas;
    }

    /**
     * Agrega una cola a una ventanilla
     *
     * @param Cola $cola
     * @return ArrayCollection
     */
    public function addCola(Cola $cola)
    {
        $this->colas[] = $cola;
        return $this->getColas();
    }

    /**
     * Remueve una cola de una ventanilla
     * @param Cola $cola
     * @return ArrayCollection
     */
    public function removeCola(Cola $cola)
    {
        $this->colas->removeElement($cola);
        return $this->getColas();
    }

    /**
     * @return ArrayCollection
     */
    public function getAgentes()
    {
        return $this->agentes;
    }

    /**
     * Get identificador
     *
     * @return string
     */
    public function getIdentificador()
    {
        return $this->identificador;
    }

    /**
     * Genera las fechas de creación y modificación de un trámite
     *
     * @ORM\PrePersist
     */
    public function setFechas()
    {
        $this->fechaCreado = new \DateTime();
        $this->fechaModificado = new \DateTime();
    }

    /**
     * Actualiza la fecha de modificación de un trámite
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
