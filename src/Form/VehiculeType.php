<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Vehicule;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'marque',
                TextType::class,
                [
                    'label' => 'Marque',
                    'attr' => [
                        'placeholder' => 'Marque du véhicule',
                        'class' => 'input',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir la marque du véhicule',
                        ]),
                    ],
                ]
            )
            ->add(
                'reference',
                TextType::class,
                [
                    'label' => 'Référence',
                    'attr' => [
                        'placeholder' => 'Référence du véhicule',
                        'class' => 'input',
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'couleur',
                ChoiceType::class,
                [
                    'label' => 'Couleur',
                    'choices'  => [
                        'Blanc' => 'blanc',
                        'Rouge' => 'rouge',
                        'Vert' => 'vert',
                        'Bleu' => 'bleu',
                        'Gris' => 'gris',
                        'Jaune' => 'vert',
                        'Autre' => null
                    ],
                    'attr' => [
                        'placeholder' => 'Couleur du véhicule',
                        'class' => 'input',
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'immatriculation',
                TextType::class,
                [
                    'label' => 'Immatriculation',
                    'attr' => [
                        'placeholder' => 'Immatriculation du véhicule',
                        'class' => 'input',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir l\'immatriculation du véhicule',
                        ]),
                    ],
                ]
            )
            ->add(
                'prix_journalier',
                MoneyType::class,
                [
                    'label' => 'Prix journalier',
                    'attr' => [
                        'placeholder' => 'Prix journalier du véhicule',
                        'class' => 'input',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez saisir le prix journalier du véhicule',
                        ]),
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'attr' => [
                        'placeholder' => 'Saisir une description',
                        'class' => 'input',
                        'rows' => 100,
                        'cols' => 33,
                        'style' => 'resize: none;',
                    ],
                    'required' => false,
                ]
            )
            ->add('status_disponibilite', ChoiceType::class, [
                'choices'  => [
                    'Disponible' => true,
                    'Indisponible' => false,
                ],
                'label' => 'Disponibilité',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir la disponibilité du véhicule',
                    ]),
                ],
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
            'data_class' => Vehicule::class,
        ]);
    }
}
