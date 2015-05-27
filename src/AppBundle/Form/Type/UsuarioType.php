<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreUsuario', 'text', array(
                'label' => 'Nombre de usuario',
                'required' => true
            ))
            ->add('dni', 'text', array(
                'label' => 'DNI',
                'required' => true
            ))
            ->add('imagen', 'file', array(
                'label' => 'Imagen de perfil',
                'required' => false
            ))
            ->add('pass', 'password', array(
                'label' => 'Contraseña',
                'required' => true
            ))
            ->add('nombreCompleto', 'text', array(
                'label' => 'Nombre completo',
                'required' => true
            ))
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos',
                'required' => true
            ))
            ->add('telefono', 'text', array(
                'label' => 'Telefono',
            ))
            ->add('enviar', 'submit', array(
                'label' => 'Registrarse',
                'attr' => array('class' => 'btn purple accent-4 waves-effect waves-light')
            ));

        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'nombreUsuraio';
    }
}