<?php

namespace App\Form;

use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Gouvernorat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $regions = [
        //     'Ariana' => 'Ariana',
        //     'Béja' => 'Béja',
        //     'Ben Arous' => 'Ben Arous',
        //     'Bizerte' => 'Bizerte',
        //     'Gabès' => 'Gabès',
        //     'Gafsa' => 'Gafsa',
        //     'Jendouba' => 'Jendouba',
        //     'Kairouan' => 'Kairouan',
        //     'Kasserine' => 'Kasserine',
        //     'Kebili' => 'Kebili',
        //     'Kef' => 'Kef',
        //     'Mahdia' => 'Mahdia',
        //     'Manouba' => 'Manouba',
        //     'Medenine' => 'Medenine',
        //     'Monastir' => 'Monastir',
        //     'Nabeul' => 'Nabeul',
        //     'Sfax' => 'Sfax',
        //     'Sidi Bouzid' => 'Sidi Bouzid',
        //     'Siliana' => 'Siliana',
        //     'Sousse' => 'Sousse',
        //     'Tataouine' => 'Tataouine',
        //     'Tozeur' => 'Tozeur',
        //     'Tunis' => 'Tunis',
        //     'Zaghouan' => 'Zaghouan',
        // ];

        $builder
            ->add('nomplat')
            ->add('restaurant')
            ->add('chef')
            ->add('image', FileType::class, ['mapped' => false])
            ->add('region', ChoiceType::class, [
                'choices' => [
                    'NORD' => 'NORD',
                    'CENTRE' => 'CENTRE',
                    'SUD' => 'SUD',
                ]
            ])
            ->add('gouvernorat', EntityType::class, [
                'class' => Gouvernorat::class,
                'choice_label' => 'nomGouver',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
