<?php

namespace App\Form;

use App\Entity\Pret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Montant')
            ->add('Interet')
            ->add('Periode')
            ->add('Type'  ,ChoiceType::class, [
        'choices' => [
            'Entreprise' => 'Entreprise',
            'Personnel' => 'Personnel',
            'Petit-Business' => 'Petit-Business',
        ],])
            ->add('description')
            ->add('Status')
            ->add('PretId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pret::class,
        ]);
    }
}
