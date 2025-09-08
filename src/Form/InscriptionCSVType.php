<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionCSVType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('lastName')
            ->add('firstName')
            ->add('phoneNumber')
            ->add('actif')
            ->add('userImage')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'id',
            ])
            ->add('outingParticipants', EntityType::class, [
                'class' => Outing::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'constraints' => [
                    new File(
                        path:
                        maxSize: '1M',
                        maxSizeMessage: "L'image ne doit pas dépasser 1 Mo",
                        extensions: ['csv'],
                        extensionsMessage: "Seulement le type .csv est autorisé"
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
