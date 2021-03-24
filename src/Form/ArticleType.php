<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('image')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('isPublished')

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
