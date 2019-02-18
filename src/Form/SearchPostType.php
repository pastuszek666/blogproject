<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SearchPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, array(
                'required' => FALSE,
            ))
            ->add('category', EntityType::class, array(
                'required' => FALSE,
                'label' => FALSE,
                'class' => 'App\Entity\Category',
                'attr' => array(
                    'class' => 'selectpicker fullwidth-select',
                    'data-placeholder' => 'Wybierz kategorię'
                )
            ))        
            ->add('status', EntityType::class, array(
                'required' => FALSE,
                'label' => FALSE,
                'class' => 'App\Entity\PostStatus',
                'attr' => array(
                    'class' => 'selectpicker fullwidth-select',
                    'data-placeholder' => 'Wybierz Status'
                )
            ))
            ->add('tags', EntityType::class, array(
                'required' => FALSE,
                'label' => FALSE,
                'class' => 'App\Entity\Tag',
                'attr' => array(
                    'class' => 'selectpicker fullwidth-select',
                    'data-placeholder' => 'Wybierz Tagi',
                )
            ))
            ;
    }
}