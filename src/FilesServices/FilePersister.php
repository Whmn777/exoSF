<?php

namespace App\FilesServices;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilePersister
{

    private $slugger;

    private $parameterBag;

    //Avec mon constructor je crée des nouvelles instances des class SluggerInterface et ParameterBagInterface.

    public function __construct(SluggerInterface $slugger, ParameterBagInterface $parameterBag)
    {
        $this->slugger = $slugger;
        $this->parameterBag = $parameterBag;
    }

    //Je crée la méthode save(File), en lui passant comme paramètre $article et $articleForm
    //En utilisant les deux instances crée au dessus. Ce mécanisme fonctionne comme l'autowire
    //dans le Controller. Ici on a fait appel à la méthode __construct().

    public function saveFile($article, $articleForm)
    {
        $image = $articleForm->get('image')->getData();

        if ($image) {
            $originalImage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeImage = $this->slugger->slug($originalImage);
            $newImage = $safeImage.'-'.uniqid().'.'.$image->guessExtension();
        }

        try {
            $image->move(
                $this->parameterBag->get('images_directory'),
                $newImage
            );
        } catch (FileException $e) {
            throw new \Exception("le fichier n'a pas pu être enregistré");
        }

        $article->setImage($newImage);

        return $article;
    }

}