<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Quel nom souhaitez-vous donner à votre adresse ? ',
                'attr'=> [
                    'placeholder' => ' ex : domicile , parent, lieu de travail'
                    ]
            ])
            ->add('firstname', TextType::class, [
                'label'=> 'prénom ',
                'attr'=> [
                    'placeholder' => 'Entrez votre prénom'
                    ]
            ])
            ->add('lastname', TextType::class, [
                'label'=> 'nom  ',
                'attr'=> [
                    'placeholder' => 'Entrez votre prénom'
                    ]
            ])
            ->add('company', TextType::class, [
                'label'=> 'Nom de votre société ',
                'attr'=> [
                    'placeholder' => 'Entrez le nom de votre société'
                    ]
            ])
            ->add('address', TextType::class, [
                'label'=> 'adresse ',
                'attr'=> [
                    'placeholder' => 'Entrez le numéro et nom de la rue '
                    ]
            ])
            ->add('postal', TextType::class, [
                'label'=> 'code postal',
                'attr'=> [
                    'placeholder' => '26000'
                    ]
            ])
            ->add('city', TextType::class, [
                'label'=> 'ville',
                'attr'=> [
                    'placeholder' => 'ex : Valence'
                    ]
            ])
            ->add('country', CountryType::class, [
                'label'=> 'Pays ',
                'attr'=> [
                    'placeholder' => 'ex : France'
                    ]
            ])
            ->add('phone', TelType::class, [
                'label'=> 'Votre numéro de téléphone ',
                'attr'=> [
                    'placeholder' => '06 .. .. .. ..'
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> 'Valider',
                'attr'=> [
                    'class' => 'btn-info w-100 mt-4'
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
