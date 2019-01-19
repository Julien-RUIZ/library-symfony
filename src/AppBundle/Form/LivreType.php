<?php

namespace AppBundle\Form;

use AppBundle\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('genre')
            ->add('nbPages')
            ->add('format',ChoiceType::class,
                [
                    'choices' =>[
                        'poche ' => ' poche',
                        'kindle ' => ' kindle'
                    ],
                    'multiple' => false,
                    'expanded' => true
                ]
            )
            ->add('titre')
            ->add('auteur', EntityType::class, [
                'class' => 'AppBundle\Entity\Auteur',
                'choice_label' => 'nom',
    ])
            ->add('image',FileType::class)
            ->add('save', SubmitType::class, array('label' => 'valide'));

    }




    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Livre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_livre';
    }


}
