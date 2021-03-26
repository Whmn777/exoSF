<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

class ArticleType extends AbstractType
{
    //En ligne de commande depuis le terminal : php bin/console make:form
    //Je créee mon gabarit de formulaire relier à mon entité Acticle
    //Je le nomme ArticleType
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('image', FileType::class,
                array('data_class' => null),
                [
                'label' => 'image',
                'required' => 'false',
                //la valeur par défault pour l'envoie en BDD est "mapped" => "true"
                //Je ne désire pas envoyer mon nom d'image directement en BDD, dc "mapped" => "false"
                //car je désire traiter mon nom d'image en lui donnant un nom unique, entre autres.
                'mapped' => 'false',
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('isPublished')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'placeholder' => ' ',
            ])

            //Je rajoute un bouton d'envoi, et je précise le type SubmitType::class)
            ->add('valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

            //Afin de pouvoir récupérer mes données dans ma BDD je lui précise l'entité à renseigner
            //grâce à Article::class
            'data_class' => Article::class,

        ]);
    }
}
