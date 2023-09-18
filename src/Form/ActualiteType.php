<?php


namespace App\Form;


use App\Entity\Actualite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Cette Methode est en charge de construire le formulaire.
     *
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('actu', TextType::class
            )
            ->add('url',TextType::class,[
                'required' => false,
            ])
            ->add('date_actu', DateTimeType::class,[
                'widget' => 'single_text'
            ])
            ->add('affiche_actu',CheckboxType::class,[
                'label' => "Afficher l'Actualité"
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn-success'],
            ])
        ;

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualite::class,
        ]);
    }
}