<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CuentaType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'Correo electrónico',
                'required' => true
            ))
            ->add('pass', 'password', array(
                'label' => 'Nueva contraseña',
                'required' => true
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
        return 'cuenta';
    }
}