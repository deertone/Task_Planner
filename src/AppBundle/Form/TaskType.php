<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $this->userId = $options['userId'];

        $builder
        ->add('name')
        ->add('description')
        ->add('realizationDate', DateType::class, ['years' => [2017, 2018, 2019, 2020]])
        ->add('priority', ChoiceType::class, ['choices'=>['wysoki'=>'wysoki', 'średni'=>'średni', 'niski'=>'niski']])
        ->add('status', ChoiceType::class, ['choices'=>['niewykonane'=>'niewykonane', 'wykonane'=>'wykonane']])
        ->add('category', EntityType::class,
                     [ "class"=> "AppBundle:Category" ,
                      "query_builder" => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->where("c.user = :user" )
                        ->setParameter("user", "$this->userId")
                        ->orderBy("c.name", "ASC");
                      }, "choice_label"=>"name",
                  ]);
        // ->add('category', EntityType::class, ['class' => 'AppBundle\Entity\Category', 'choice_label' => 'name']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task',
            'userId' => true,
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
