<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name')
        ->add('description')
        ->add('realizationDate')
        ->add('priority', 'choice', array('choices'=>array('high'=>'high', 'medium'=>'medium', 'low'=>'low')))
        ->add('status', 'choice', array('choices'=>array('incompleted'=>'incompleted', 'completed'=>'completed', 'in progress'=>'in progress')))
        ->add('category', 'entity', ['class' => 'AppBundle\Entity\Category', 'choice_label' => 'name'])
        ->add('user', 'entity', ['class' => 'AppBundle\Entity\User', 'choice_label' => 'username']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_task';
    }


}
