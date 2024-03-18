<?php

namespace App\Form;

use App\Entity\Bureau;
use App\Entity\Referent;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class AdminUserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Adhérent' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('telephone', TelType::class, [
                'required' => true
            ])
            ->add('email')
            ->add('referents', EntityType::class, [
                'class' => Referent::class,
                'label' => 'Référents',
                'multiple' => true, // Permet la sélection de plusieurs référents
                'expanded' => true, // Affiche les référents sous forme de cases à cocher
                'attr' => ['data-max-options' => 3], // Limite le nombre maximum d'options sélectionnables à 3
                'placeholder' => 'Sélectionner une fonction référent',
                'choices' => $options['referents'], // Utilisez les referents passés en option
                'constraints' => [
                    new Count([
                        'max' => 3,
                        'maxMessage' => 'Vous ne pouvez pas sélectionner plus de 3 référents.',
                    ]),
                ],
            ])
            ->add('bureau', EntityType::class, [
                'required' => false,
                'class' => Bureau::class,
                'label' => 'Bureau',
                'placeholder' => 'Sélectionner un rôle du bureau',
                'choices' => $options['bureau'], // Utilisez le bureau passés en option
            ])
            #class birthday pour que les années soient dispos jusque 1901#
            ->add('date_naissance', BirthdayType::class, [
                # 'placeholder'=>'selectionner une valeur',
                'widget' => 'single_text'
            ])
            ->add('photos', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn-success'],
            ]);
            // Data transformer, pour gerer les rôles
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'referents' => null,
            'bureau' => null,
        ]);
    }
}
