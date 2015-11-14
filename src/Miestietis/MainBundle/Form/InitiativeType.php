<?php

namespace Miestietis\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class InitiativeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('initiative_date','datetime', array(
//                'widget' => 'single_text',
//                // this is actually the default format for single_text
//                'format' => 'yyyy-MM-dd',
//            ))
            ->add('description', 'textarea',array('data'=>'Aprašymas'))
            ->add('save','submit',array('label'=>'Pateikti'));
    }

    public function getName()
    {
        return 'Miestietis_MainBundle_Initiative';
    }
}