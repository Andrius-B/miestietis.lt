<?php

namespace Miestietis\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class InitiativeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('initiative_date','datetime',
//    'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day')
//            ))
            ->add('description', 'textarea',array('data'=>'Aprašymas'))
            ->add('save','submit',array('label'=>'Pateikti'));
    }

    public function getName()
    {
        return 'Miestietis_MainBundle_Initiative';
    }
}
