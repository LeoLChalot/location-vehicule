<?php

namespace App\Form;

use App\Entity\User;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                TextType::class,
                [
                    'label' => 'Adresse email',
                    'attr' => [
                        'placeholder' => 'Adresse email',
                        'class' => 'input'
                    ]
                ]
            )
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle(s)',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'attr' => [
                    'class' => 'input'
                ],
                'required' => true,
                'multiple' => true,
                
                
            ])
            // ->add('password', TextType::class, [
            //     'label' => 'Mot de passe',
            //     'attr' => [
            //         'placeholder' => 'Mot de passe',
            //         'class' => 'input'
            //     ]
            // ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => [
                    'placeholder' => 'Pseudo',
                    'class' => 'input'
                ],
                'required' => false,
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => 'input'
                ],
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => 'input'
                ],
                'required' => false,
            ])
            ->add('imageFile', VichFileType::class, [
                'mapped' => false,
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'Image du véhicule',
                    'class' => 'file-input',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
