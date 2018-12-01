<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateNaissance', DateType::class,['label'=>'Naissance', 'widget'=>'single_text', 'placeholder' => 'YYYY-MM-dd'])
            ->add('pays')
            ->add('dateMort', DateType::class, ['label'=>'Décès', 'widget'=>'single_text', 'placeholder' => 'YYYY-MM-dd', 'required'=>false])
            ->add('biographie', TextareaType::class, array('attr'=>array('placeholder' => 'bio')))
            ->add('save', SubmitType::class, array('label' => 'valide'))
            ;



    }







    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Auteur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_auteur';
    }


}
