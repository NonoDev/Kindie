<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CuentaEmailType extends AbstractType{

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
            ->add('enviar', 'submit', array(
                'label' => 'Cambiar email',
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
        return 'cuentaEmail';
    }
}