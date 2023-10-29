<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
     ->add('ref')
     ->add('title')
            ->add('category',ChoiceType::class,[
                'choices' => [
                    'Roman' => 'Roman',
                    'Science-fiction' => 'Science-fiction',
                    'Policier' => 'Policier',
                    'Fantastique' => 'Fantastique',
                    'Biographie' => 'Biographie',
                    'Autre' => 'Autre',
                ],
            ])
            ->add('publicationDate',DateType::class,[
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('published')
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
            ]);
    
            
        
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
