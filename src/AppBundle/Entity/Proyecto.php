<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity("nombre")
 */
class Proyecto
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
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $nombre;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $contribuciones;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $recompensa;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $localizacion;

    /**
     * @ORM\Column(type="date")
     *
     * @var \Datetime
     */
    protected $fechaInicio;

    /**
     * @ORM\Column(type="date")
     *
     * @var \Datetime
     */
    protected $fechaFin;


    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $imagenPrincipal;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $descripcionCorta;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    protected $meta;

    /**
     *
     * @ORM\Column(type="integer")
     *
     *
     * @var integer
     */
    protected $visitas;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esValido;

    /**
     * @ORM\OneToMany(targetEntity="Favorito", mappedBy="proyecto")
     *
     * @var Favorito
     */
    protected $favoritos = null;

    /**
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="proyecto")
     *
     * @var Comentario
     */
    protected $comentarios = null;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Genero", inversedBy="proyecto")
     *
     * @var Genero
     */
    protected $generos = null;

    /**
     * @ORM\OneToMany(targetEntity="Multimedia", mappedBy="proyecto")
     *
     * @var Multimedia
     */
    protected $multimedia = null;

    /**
     * @ORM\OneToMany(targetEntity="Inversion", mappedBy="proyecto")
     *
     * @var Inversion
     */
    protected $inversiones = null;

    /**
     * @ORM\OneToMany(targetEntity="Desarrollo", mappedBy="proyecto")
     *
     * @var Desarrollo
     */
    protected $actualizaciones = null;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="proyectos")
     *
     * @var Usuario
     */
    protected $usuario;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="participaciones")
     *
     * @var Usuario
     */
    protected $participantes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->multimedia = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inversiones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actualizaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participantes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nombre
     *
     * @param string $nombre
     * @return Proyecto
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
     * Set visitas
     *
     * @param integer $visitas
     * @return Proyecto
     */
    public function setVisitas($visitas)
    {
        $this->visitas = $visitas;

        return $this;
    }

    /**
     * Get visitas
     *
     * @return integer
     */
    public function getVisitas()
    {
        return $this->visitas;
    }

    /**
     * Set esValido
     *
     * @param boolean $esValido
     * @return Proyecto
     */
    public function setEsValido($esValido)
    {
        $this->esValido = $esValido;

        return $this;
    }

    /**
     * Get esValido
     *
     * @return boolean
     */
    public function getEsValido()
    {
        return $this->esValido;
    }

    /**
     * Set contribuciones
     *
     * @param float $contribuciones
     * @return Proyecto
     */
    public function setContribuciones($contribuciones)
    {
        $this->contribuciones = $contribuciones;

        return $this;
    }

    /**
     * Get contribuciones
     *
     * @return float 
     */
    public function getContribuciones()
    {
        return $this->contribuciones;
    }

    /**
     * Set recompensa
     *
     * @param string $recompensa
     * @return Proyecto
     */
    public function setRecompensa($recompensa)
    {
        $this->recompensa = $recompensa;

        return $this;
    }

    /**
     * Get recompensa
     *
     * @return string 
     */
    public function getRecompensa()
    {
        return $this->recompensa;
    }

    /**
     * Set localizacion
     *
     * @param string $localizacion
     * @return Proyecto
     */
    public function setLocalizacion($localizacion)
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    /**
     * Get localizacion
     *
     * @return string 
     */
    public function getLocalizacion()
    {
        return $this->localizacion;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Proyecto
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     * @return Proyecto
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }


    /**
     * Set imagenPrincipal
     *
     * @param string $imagenPrincipal
     * @return Proyecto
     */
    public function setImagenPrincipal($imagenPrincipal)
    {
        $this->imagenPrincipal = $imagenPrincipal;

        return $this;
    }

    /**
     * Get imagenPrincipal
     *
     * @return string 
     */
    public function getImagenPrincipal()
    {
        return $this->imagenPrincipal;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Proyecto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set descripcionCorta
     *
     * @param string $descripcionCorta
     * @return Proyecto
     */
    public function setDescripcionCorta($descripcionCorta)
    {
        $this->descripcionCorta = $descripcionCorta;

        return $this;
    }

    /**
     * Get descripcionCorta
     *
     * @return string
     */
    public function getDescripcionCorta()
    {
        return $this->descripcionCorta;
    }

    /**
     * Set meta
     *
     * @param float $meta
     * @return Proyecto
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get meta
     *
     * @return float 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set favoritos
     *
     * @param \AppBundle\Entity\Favorito $favoritos
     * @return Proyecto
     */
    public function setFavoritos(\AppBundle\Entity\Favorito $favoritos = null)
    {
        $this->favoritos = $favoritos;

        return $this;
    }

    /**
     * Get favoritos
     *
     * @return \AppBundle\Entity\Favorito 
     */
    public function getFavoritos()
    {
        return $this->favoritos;
    }

    /**
     * Add comentarios
     *
     * @param \AppBundle\Entity\Comentario $comentarios
     * @return Proyecto
     */
    public function addComentario(\AppBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \AppBundle\Entity\Comentario $comentarios
     */
    public function removeComentario(\AppBundle\Entity\Comentario $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add generos
     *
     * @param \AppBundle\Entity\Genero $generos
     * @return Proyecto
     */
    public function addGeneros(\AppBundle\Entity\Genero $generos)
    {
        $this->generos[] = $generos;

        return $this;
    }

    /**
     * Remove generos
     *
     * @param \AppBundle\Entity\Genero $generos
     */
    public function removeGeneros(\AppBundle\Entity\Genero $generos)
    {
        $this->comentarios->removeElement($generos);
    }

    /**
     * Get generos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGeneros()
    {
        return $this->generos;
    }

    /**
     * Add multimedia
     *
     * @param \AppBundle\Entity\Multimedia $multimedia
     * @return Proyecto
     */
    public function addMultimedia(\AppBundle\Entity\Multimedia $multimedia)
    {
        $this->multimedia[] = $multimedia;

        return $this;
    }

    /**
     * Remove multimedia
     *
     * @param \AppBundle\Entity\Multimedia $multimedia
     */
    public function removeMultimedia(\AppBundle\Entity\Multimedia $multimedia)
    {
        $this->multimedia->removeElement($multimedia);
    }

    /**
     * Get multimedia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMultimedia()
    {
        return $this->multimedia;
    }

    /**
     * Add inversiones
     *
     * @param \AppBundle\Entity\Inversion $inversiones
     * @return Proyecto
     */
    public function addInversione(\AppBundle\Entity\Inversion $inversiones)
    {
        $this->inversiones[] = $inversiones;

        return $this;
    }

    /**
     * Remove inversiones
     *
     * @param \AppBundle\Entity\Inversion $inversiones
     */
    public function removeInversione(\AppBundle\Entity\Inversion $inversiones)
    {
        $this->inversiones->removeElement($inversiones);
    }

    /**
     * Get inversiones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInversiones()
    {
        return $this->inversiones;
    }

    /**
     * Add actualizaciones
     *
     * @param \AppBundle\Entity\Desarrollo $actualizaciones
     * @return Proyecto
     */
    public function addActualizacione(\AppBundle\Entity\Desarrollo $actualizaciones)
    {
        $this->actualizaciones[] = $actualizaciones;

        return $this;
    }

    /**
     * Remove actualizaciones
     *
     * @param \AppBundle\Entity\Desarrollo $actualizaciones
     */
    public function removeActualizacione(\AppBundle\Entity\Desarrollo $actualizaciones)
    {
        $this->actualizaciones->removeElement($actualizaciones);
    }

    /**
     * Get actualizaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActualizaciones()
    {
        return $this->actualizaciones;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuario $usuario
     * @return Proyecto
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Add participantes
     *
     * @param \AppBundle\Entity\Usuario $participantes
     * @return Proyecto
     */
    public function addParticipante(\AppBundle\Entity\Usuario $participantes)
    {
        $this->participantes[] = $participantes;

        return $this;
    }

    /**
     * Remove participantes
     *
     * @param \AppBundle\Entity\Usuario $participantes
     */
    public function removeParticipante(\AppBundle\Entity\Usuario $participantes)
    {
        $this->participantes->removeElement($participantes);
    }

    /**
     * Get participantes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }

    /**
     * Set generos
     *
     * @param \AppBundle\Entity\Genero $generos
     * @return Proyecto
     */
    public function setGeneros(\AppBundle\Entity\Genero $generos = null)
    {
        $this->generos = $generos;

        return $this;
    }
}
