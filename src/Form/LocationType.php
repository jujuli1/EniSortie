<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => fn(City $city) => $city->getName() . ' (' . $city->getPostalCode() . ')',
                'placeholder' => 'Choisir une ville',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du lieu',
                'attr' => ['data-autocomplete' => 'location-name']
            ])
            ->add('street', TextType::class)
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
