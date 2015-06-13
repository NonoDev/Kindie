<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 */
class Multimedia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="text", length=255, nullable=true)
     *
     * @var string
     */
    protected $ruta;

    /**
     * @ORM\ManyToOne(targetEntity="Proyecto", inversedBy="multimedia")
     *
     * @var Proyecto
     */
    protected $proyecto = null;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set proyecto
     *
     * @param \AppBundle\Entity\Proyecto $proyecto
     * @return Multimedia
     */
    public function setProyecto(\AppBundle\Entity\Proyecto $proyecto = null)
    {
        $this->proyecto = $proyecto;

        return $this;
    }

    /**
     * Get proyecto
     *
     * @return \AppBundle\Entity\Proyecto 
     */
    public function getProyecto()
    {
        return $this->proyecto;
    }

    /**
     * @param string $ruta
     * @return Multimedia
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;
        return $this;
    }
    /**
     * @return string
     */
    public function getRuta()
    {
        return $this->ruta;
    }
    /**
     * Called before saving the entity
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */

}
