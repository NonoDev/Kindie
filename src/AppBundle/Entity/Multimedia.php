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
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var File
     * @Assert\File(    maxSize = "5M",
     *                  mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *                  maxSizeMessage = "El tamaño máximo de imágen es de 5MB.",
     *                  mimeTypesMessage = "Solo se aceptan archivos de tipo Imágen.")
     */
    protected $imagen;

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
     * Set contenido
     *
     * @param string $contenido
     * @return Multimedia
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
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
    public function preUpload()
    {
        if (null !== $this->imagen) {
            $imagen = sha1(uniqid(mt_rand(), true));
            $this->ruta = $imagen.'.'.$this->imagen->guessExtension();
        }
    }
    /**
     * Called before entity removal
     *
     * @ORM\PreRemove()
     */
    public function removeUpload()
    {
        if ($imagen = $this->getAbsolutePath()) {
            unlink($imagen);
        }
    }
    /**
     * Called after entity persistence
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->imagen) {
            return;
        }
        $a = $this->imagen->move(
            $this->getUploadRootDir(),
            $this->ruta
        );
        dump($a);
        $this->imagen = null;
    }
    /**
     * @return string
     */
    public function getUploadDir()
    {
        return 'uploads/img';
    }
    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }
    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return null === $this->ruta
            ? null : $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->ruta;
    }
    /**
     * @return string
     */
    public function getWebPath()
    {
        return null === $this->ruta
            ? null : $this->getUploadDir() . DIRECTORY_SEPARATOR . $this->ruta;
    }
    /**
     * @param UploadedFile $imagen
     */
    public function setImagen(UploadedFile $imagen = null)
    {
        $this->imagen = $imagen;
    }
    /**
     * @return UploadedFile
     */
    public function getImagen()
    {
        return $this->imagen;
    }
}
