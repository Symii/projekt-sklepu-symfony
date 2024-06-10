<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'attr' => ['placeholder' => 'Wpisz swoje imię']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => ['placeholder' => 'Wpisz swoje nazwisko']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adres',
                'attr' => ['placeholder' => 'Wpisz swój adres']
            ])
            // Dodaj inne pola formularza według potrzeb
            ->add('submit', SubmitType::class, [
                'label' => 'Złóż zamówienie',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Konfiguracja klasy docelowej dla formularza (jeśli wymagana)
            // 'data_class' => YourEntity::class,  // Jeśli używasz obiektu encji
        ]);
    }
}