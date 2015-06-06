<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActualizacionType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', 'text', array(
                'label' => 'Título',
                'required' => true
            ))
            ->add('texto', 'textarea', array(
                'label' => 'Indica los avances de tu proyecto',
                'required' => true,
                'attr' => array('class' => 'materialize-textarea', 'length' => 500)
            ))

            ->add('comentar', 'submit', array(
                'label' => 'Añadir',
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
        return 'desarrollo';
    }
}