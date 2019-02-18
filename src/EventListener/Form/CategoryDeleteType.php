<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\Category;



class CategoryDeleteType extends AbstractType
{
//    private $category;
//
//    public function __construct($category)
//    {
//        $this->category = $category;
//    }

    public function getName()
    {
        return 'deleteCategory';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $this->category;

        $builder
                ->add('setNull', CheckboxType::class, [
                    'label' => 'Ustaw wszystkie posty bez kategorii',
                    'required' => false,
                ])
                ->add('newCategory', EntityType::class, [
                    'label' => false,
                    'class' => 'App\Entity\Category',
                    'query_builder' => function (EntityRepository $er) use ($category) {
                        return $er->createQueryBuilder('category')
                                ->where('category.id != :categoryId')
                                ->setParameter('categoryId', $category->getId());
                    },
                    'required' => false,
                    'label_attr' => [
                        'class' => 'floated',
                    ],
                ]);
    }

//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Category::class,
//        ]);
//    }
}
