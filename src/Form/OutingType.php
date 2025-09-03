<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Outing;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\StatusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie : ',
            ])
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label'  => 'Date et heure de la sortie : '
            ])
            ->add('registrationLimitDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label'  => 'Date limite d\'inscription : '
            ])
            ->add('nbMaxRegistration', IntegerType::class, [
                'label' => 'Nombre de places : '
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée : '
            ])
            ->add('outingInfos', TextareaType::class, [
                'label' => 'Description et infos : ',
                'attr' => [
                    'rows' => 6
                ]
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'label' => 'Campus',
                'query_builder' => function (CampusRepository $er) {
                    return $er->createQueryBuilder('c')->addOrderBy('c.name');
                }
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
                'label' => 'Lieu : ',
                'query_builder' => function (LocationRepository $er) {
                     return $er->createQueryBuilder('l')->addOrderBy('l.name');
                }
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
