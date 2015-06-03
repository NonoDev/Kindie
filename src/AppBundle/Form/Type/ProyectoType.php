<?php


namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProyectoType extends AbstractType{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$builder
            ->add('nombre', 'text', array(
                'label' => 'Nombre del proyecto',
                'required' => true
            ))
            ->add('recompensa', 'text', array(
                'label' => 'Recompensa',
                'required' => true
            ))
            ->add('imagenPrincipal', 'file', array(
                'label' => 'Imagen Principal del proyecto',
                'required' => false
            ))
            ->add('localizacion', 'text', array(
                'label' => 'Localización',
                'required' => true
            ))
            ->add('fechaFin', 'datetime', array(
                'label' => 'Fecha límite',
                'required' => true,
                'attr' => array('class' => 'datepicker')
            ))
            ->add('descripcionCorta', 'text', array(
                'label' => 'Descripción corta (se mostrará en la miniatura del proyecto)',
                'required' => true
            ))
            ->add('meta', 'number', array(
                'label' => 'Cantidad necesaria',
                'required' => true
            ))
            ->add('crear', 'submit', array(
                'label' => 'Crear',
                'attr' => array('class' => 'btn purple accent-4 waves-effect waves-light')
            ));
*/

    }

    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'proyecto';
    }
}