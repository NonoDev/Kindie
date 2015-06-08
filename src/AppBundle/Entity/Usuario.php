<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 */
class Usuario implements UserInterface
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
    protected $dni;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $imagen;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $pass;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $nombreCompleto;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $nombreUsuario;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esAdmin;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esCreador;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    protected $esParticipante;


    /**
     * @ORM\OneToMany(targetEntity="Favorito", mappedBy="usuario")
     *
     * @var Favorito
     */
    protected $favoritos = null;

    /**
     * @ORM\OneToMany(targetEntity="Comentario", mappedBy="usuario")
     *
     * @var Comentario
     */
    protected $comentarios = null;

    /**
     * @ORM\OneToMany(targetEntity="Notificacion", mappedBy="usuario")
     *
     * @var Notificacion
     */
    protected $notificaciones = null;

    /**
     * @ORM\OneToMany(targetEntity="Mensaje", mappedBy="usuario")
     *
     * @var Mensaje
     */
    protected $mensajes = null;

    /**
     * @ORM\OneToMany(targetEntity="Inversion", mappedBy="usuario")
     *
     * @var Inversion
     */
    protected $inversiones = null;

    /**
     * @ORM\OneToMany(targetEntity="Proyecto", mappedBy="usuario")
     *
     * @var Proyecto
     */
    protected $proyectos = null;

    /**
     * @ORM\ManyToMany(targetEntity="Proyecto", mappedBy="participantes")
     *
     * @var Proyecto
     */
    protected $participaciones = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->favoritos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comentarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notificaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inversiones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set dni
     *
     * @param string $dni
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set imagen
     *
     * @param string $imagen
     * @return Usuario
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set pass
     *
     * @param string $pass
     * @return Usuario
     */
    public function setPass($pass)
    {
        $this->pass = $pass;

        return $this;
    }

    /**
     * Get pass
     *
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     * @return Usuario
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     * @return Usuario
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set esAdmin
     *
     * @param boolean $esAdmin
     * @return Usuario
     */
    public function setesAdmin($esAdmin)
    {
        $this->esAdmin = $esAdmin;

        return $this;
    }

    /**
     * Get esAdmin
     *
     * @return boolean
     */
    public function getesAdmin()
    {
        return $this->esAdmin;
    }

    /**
     * Set esCreador
     *
     * @param boolean $esCreador
     * @return Usuario
     */
    public function setEsCreador($esCreador)
    {
        $this->esCreador = $esCreador;

        return $this;
    }

    /**
     * Get esCreador
     *
     * @return boolean
     */
    public function getEsCreador()
    {
        return $this->esCreador;
    }

    /**
     * Set esParticipante
     *
     * @param boolean $esParticipante
     * @return Usuario
     */
    public function setEsParticipante($esParticipante)
    {
        $this->esParticipante = $esParticipante;

        return $this;
    }

    /**
     * Get esParticipante
     *
     * @return boolean
     */
    public function getEsParticipante()
    {
        return $this->esCreador;
    }

    /**
     * Add favoritos
     *
     * @param \AppBundle\Entity\Favorito $favoritos
     * @return Usuario
     */
    public function addFavorito(\AppBundle\Entity\Favorito $favoritos)
    {
        $this->favoritos[] = $favoritos;

        return $this;
    }

    /**
     * Remove favoritos
     *
     * @param \AppBundle\Entity\Favorito $favoritos
     */
    public function removeFavorito(\AppBundle\Entity\Favorito $favoritos)
    {
        $this->favoritos->removeElement($favoritos);
    }

    /**
     * Get favoritos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoritos()
    {
        return $this->favoritos;
    }

    /**
     * Add comentarios
     *
     * @param \AppBundle\Entity\Comentario $comentarios
     * @return Usuario
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
     * Add notificaciones
     *
     * @param \AppBundle\Entity\Notificacion $notificaciones
     * @return Usuario
     */
    public function addNotificacione(\AppBundle\Entity\Notificacion $notificaciones)
    {
        $this->notificaciones[] = $notificaciones;

        return $this;
    }

    /**
     * Remove notificaciones
     *
     * @param \AppBundle\Entity\Notificacion $notificaciones
     */
    public function removeNotificacione(\AppBundle\Entity\Notificacion $notificaciones)
    {
        $this->notificaciones->removeElement($notificaciones);
    }

    /**
     * Get notificaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificaciones()
    {
        return $this->notificaciones;
    }

    /**
     * Add mensajes
     *
     * @param \AppBundle\Entity\Mensaje $mensajes
     * @return Usuario
     */
    public function addMensaje(\AppBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \AppBundle\Entity\Mensaje $mensajes
     */
    public function removeMensaje(\AppBundle\Entity\Mensaje $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }

    /**
     * Add inversiones
     *
     * @param \AppBundle\Entity\Inversion $inversiones
     * @return Usuario
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
     * Add proyectos
     *
     * @param \AppBundle\Entity\Proyecto $proyectos
     * @return Usuario
     */
    public function addProyecto(\AppBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos[] = $proyectos;

        return $this;
    }

    /**
     * Remove proyectos
     *
     * @param \AppBundle\Entity\Proyecto $proyectos
     */
    public function removeProyecto(\AppBundle\Entity\Proyecto $proyectos)
    {
        $this->proyectos->removeElement($proyectos);
    }

    /**
     * Get proyectos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectos()
    {
        return $this->proyectos;
    }

    /**
     * Add participaciones
     *
     * @param \AppBundle\Entity\Proyecto $participaciones
     * @return Usuario
     */
    public function addParticipacione(\AppBundle\Entity\Proyecto $participaciones)
    {
        $this->participaciones[] = $participaciones;

        return $this;
    }

    /**
     * Remove participaciones
     *
     * @param \AppBundle\Entity\Proyecto $participaciones
     */
    public function removeParticipacione(\AppBundle\Entity\Proyecto $participaciones)
    {
        $this->participaciones->removeElement($participaciones);
    }

    /**
     * Get participaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipaciones()
    {
        return $this->participaciones;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $roles = array(new Role('ROLE_USER'));
        if ($this->getesAdmin()) {
            $roles[] = new Role('ROLE_ADMIN');
        }
        if ($this->getEsCreador()) {
            $roles[] = new Role('ROLE_CREADOR');
        }
        if ($this->getEsParticipante()) {
            $roles[] = new Role('ROLE_PARTICIPANTE');
        }
        dump($roles);
        return $roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getPass();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->getNombreUsuario();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
