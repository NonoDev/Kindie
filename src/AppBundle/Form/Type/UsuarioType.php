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
                'label' => 'Nombre de usuario*',
                'max_length' => 16,
                'required' => true,
                'attr' => (array('length' => 16))
            ))
            ->add('email', 'email', array(
                'label' => 'Email*',
                'required' => true
            ))
            ->add('dni', 'text', array(
                'label' => 'DNI*',
                'max_length' => 9,
                'required' => true
            ))
            ->add('pass', 'password', array(
                'label' => 'Contraseña* (entre 4-12 caracteres)',
                'attr' => (array('length' => 12)),
                'required' => true
            ))
            ->add('nombreCompleto', 'text', array(
                'label' => 'Nombre completo*',
                'required' => true
            ))
            ->add('apellidos', 'text', array(
                'label' => 'Apellidos*',
                'required' => true
            ))
            ->add('telefono', 'number', array(
                'label' => 'Telefono',
                'max_length' => 9
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