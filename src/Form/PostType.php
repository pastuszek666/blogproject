<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\PostStatus;
use Symfony\Component\Validator\Constraints\Length;

class PostType extends AbstractType
{
    public function getName()
    {
        return 'post';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
                    'label' => 'form.post.title',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '120',
                    ],
                ])
                ->add('slug', TextType::class, [
                    'label' => 'form.post.slug',
                    'required' => false,
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '120',
                    ],
                ])
                ->add('category', EntityType::class, [
                    'label' => false,
                    'class' => Category::class,
                ])
                ->add('status', EntityType::class, [
                    'label' => false,
                    'class' => PostStatus::class,
                ])
                ->add('tags', EntityType::class, [
                    'label' => false,
                    'multiple' => true,
                    'class' => Tag::class,
                    'attr' => [
                        'multiple' => 'multiple',
                    ],
                ])
                ->add('metaTitle', TextType::class, [
                    'label' => 'form.post.meta_title',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '60',
                    ],
                ])
                ->add('metaDescription', TextareaType::class, [
                    'label' => 'form.post.meta_description',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '160',
                        'rows' => 3,
                    ],
                ])

                ->add('content', TextareaType::class, [
                    'label' => 'form.post.content',
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '1000000',
                    ],
                ])
                ->add('introductionContent', TextareaType::class, [
                    'label' => 'form.post.introduction_content',
                    'attr' => [
                        'data-minlength' => '3',
                        'data-maxlength' => '500',
                        'rows' => 4,
                    ],
                ])->add('thumbnail', FileType::class, [
                'required' => false,
                'label' => false,
            ])

                ->add('publishedDate', DateTimeType::class, [
                    'widget' => 'single_text',
//                    'html5' => false,
//                    'date_format' => 'YYYY-mm-dd H:i:s',
//                    'input' => 'datetime',
                    'label' => false,
                ])

               ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
