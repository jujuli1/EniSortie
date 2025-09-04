<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Tous les campus',
            ])
            ->add('NameContentWord', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'required' => false,
                'label' => 'Le nom de la sortie contient : ',
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label'  => 'Entre'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label'  => 'et'
            ])
            ->add('isOrganizer', checkboxType::class, [
                'required' => false,
                'label' => 'Sorties dont je suis organisateur/trice'
            ])
            ->add('isParticipant', checkboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je suis inscrit/e'
            ])
            ->add('isNotParticipant', checkboxType::class, [
                'required' => false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e'
            ])
            ->add('isPassed', checkboxType::class, [
                'required' => false,
                'label' => 'Sorties passées'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET', // Conserve URL filter
            'csrf_protection' => false,

        ]);
    }
}