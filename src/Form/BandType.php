<?php

namespace App\Form;

use App\Entity\Band;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom du Groupe'])
            ->add('origin', TextType::class, ['label' => 'Origine'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('startYear', IntegerType::class, ['label' => 'Année début'])
            ->add('separationYear', IntegerType::class, ['label' => 'Année séparation', 'required' => false])
            ->add('founders', TextType::class, ['label' => 'Fondateurs', 'required' => false])
            ->add('members', IntegerType::class, ['label' => 'Membres', 'required' => false])
            ->add('musicalCurrent', TextType::class, ['label' => 'Courant Musical', 'required' => false])
            ->add('presentation', TextType::class, ['label' => 'Présentation']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Band::class,
        ]);
    }
}
