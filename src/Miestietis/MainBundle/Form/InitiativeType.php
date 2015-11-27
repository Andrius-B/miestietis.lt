<?php

namespace Miestietis\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class InitiativeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('initiative_date', 'datetime', array(
            'date_format' => 'yyyy-MM-dd',
            'input'  => 'string',
            'widget' => 'choice',
            'years' => range((int)date("Y"),(int)date("Y")+20), //20 year range
            'months' => range(1,12),
            'with_seconds' => false,
            'placeholder' => array('year' => 'Metai', 'month' => 'Mėnuo', 'day' => 'Diena', 'hour'=>'Valanda', 'minute'=>'Minutė'),
            'label' => 'Iniciatyvos data'
            ))
            ->add('description', 'textarea')
            ->add('save','submit',array('label'=>'Pateikti'));
    }

    public function getName()
    {
        return 'Miestietis_MainBundle_Initiative';
    }
}
