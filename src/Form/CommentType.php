<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('createdAt')
            ->add('rating', IntegerType::class, [
                'label' => 'Votre note', 
                'attr' => [
                    'placeholder' => 'Merci de partager votre note',
                    'min' => 1,
                    'max' => 5,
                    'step' => 1
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre message', 
                'attr' => [
                    'placeholder' => 'Merci de saisir votre message'
                ]
            ])
            // ->add('product')
            // ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
