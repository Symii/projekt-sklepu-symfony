<?php
// src/Form/ProductType.php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa produktu',
            ])
            ->add('description', TextType::class, [
                'label' => 'Opis produktu',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Cena',
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Ilosc w magazynie',
                'data' => 0,  // Domyślna wartość
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // Pole do wyświetlenia w formularzu, np. nazwa kategorii
                'label' => 'Kategoria',
            ])
            ->add('imageUrl', TextType::class, [
                'label' => 'Link do zdjecia',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}