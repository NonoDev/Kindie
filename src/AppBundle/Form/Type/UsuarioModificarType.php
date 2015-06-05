<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UsuarioModificarType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreUsuario', 'text', array(
                'label' => 'Nuevo nombre de usuario',
                'required' => true
            ))
            ->add('dni', 'text', array(
                'label' => 'Nuevo DNI',
                'required' => true
            ))
            ->add('imagen', 'file', array(
                'label' => 'Nueva imagen de perfil',
                'required' => false
            ))
            ->add('nombreCompleto', 'text', array(
                'label' => 'Nuevo nombre completo',
                'required' => true
            ))
            ->add('apellidos', 'text', array(
                'label' => 'Nuevos apellidos',
                'required' => true
            ))
            ->add('telefono', 'text', array(
                'label' => 'Nuevo telefono',
            ))
            ->add('enviar', 'submit', array(
                'label' => 'Guardar cambios',
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