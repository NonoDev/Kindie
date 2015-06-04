<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class MensajeType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo', 'text', array(
                'label' => 'Asunto',
                'required' => true
            ))
            ->add('texto', 'textarea', array(
                'label' => 'Mensaje',
                'required' => true,
                'attr' => array('class' => 'materialize-textarea', 'length' => 500)
            ))

            ->add('enviar', 'submit', array(
                'label' => 'Enviar mensaje',
                'attr' => array('class' => 'btn purple accent-4 waves-effect waves-light')
            ))

        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'mensaje';
    }
}