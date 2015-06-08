<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ImagenType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagen', 'file', [
                'label' => 'Añadir fotografía',
                'data_class' => null,
                'required' => true
            ])
            ->add('guardar', 'submit', array(
                'label' => 'Guardar cambios',
                'attr' => array('class' => 'btn purple accent-4 waves-effect waves-light')
    ));

    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'imagen';
    }
}