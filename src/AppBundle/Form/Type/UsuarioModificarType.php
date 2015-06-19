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
                'max_length' => 16,
                'attr' => (array('length' => 16)),
                'required' => true
            ))
            ->add('dni', 'text', array(
                'label' => 'Nuevo DNI',
                'max_length' => 9,
                'required' => true
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
                'attr' => (array('length' => 9)),
                'max_length' => 9
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