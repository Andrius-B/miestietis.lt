<?php

namespace Miestietis\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class InitiativeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = array();
        $current = (int)date("Y");
        for($i=0;$i<=20;$i+=1){ //20 upcomming years
            $years[]=(string)($current+$i);
        }
        $builder->add('initiative_date', 'date', array(
            'input'  => 'string',
            'widget' => 'choice',
            'years' => $years,
            'format' => 'yyyy-MM-dd',
            'placeholder' => array('year' => 'Metai', 'month' => 'Mėnuo', 'day' => 'Diena'),
            'label' => 'Iniciatyvos data'
            //'html5' => 'true',
            //'placeholder' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
            ))
            ->add('description', 'textarea')
            ->add('save','submit',array('label'=>'Pateikti'));
    }

    public function getName()
    {
        return 'Miestietis_MainBundle_Initiative';
    }
}
