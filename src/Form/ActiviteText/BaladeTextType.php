<?php

namespace App\Form\ActiviteText;

use App\Entity\ActiviteContent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaladeTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('BaladeTitle',TextareaType::class, [
                'label' => 'Modifier le titre :'
            ])
            ->add('BaladeText',TextareaType::class, [
                'label' => 'Modifier le texte :'
            ])
            ->add('BaladePhoto',FileType::class, [
                'label' => 'Modifier la photo :',
                'mapped' => false,
                'required' => false
            ])
            ->add('Enregistrer',SubmitType::class, [
                'attr' => ['class' => 'btn-success'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ActiviteContent::class,
        ]);
    }
}