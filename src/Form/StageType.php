<?php

namespace App\Form;

use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class, array('label' => 'Nazwa',
          ))
          ->add('award', TextType::class, array('label' => 'Nagroda',
          ))
          ->add('endDate', DateType::class, array(
              'format' => 'yyyy-MM-dd', 'widget' => 'single_text','input' => 'datetime', 'html5' => false,
          ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Stage::class,
            'csrf_protection' => false,
        ));
    }
}
