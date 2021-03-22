<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function searchByTerm($search)
    {
        //Je crée une nouvelle requête $queryBuilder pour récupérer tous les articles
        // contenant la valeur de notre input de recherche grâce à la méthode createQueryBuilder()

        //de la class EntityRepository:

        $queryBuilder = $this->createQueryBuilder("a"); //Je lui attribue un alias en paramètre

        //Je stocke cette nouvelle requête de recherche dans une variable $query et en y définnisant les termes :
        $query = $queryBuilder
            ->select('a')
            ->where('a.content LIKE :search')
        //SELECT a.* FROM myclass AS a WHERE a.content LIKE :search'

        //Avec Doctrine on lie les paramètres à notre requête (liaison dynamique) => préparation de requête pour
        //plus de sécurité
        //L'appel de setParameter () déduit automatiquement le type que nous définissons comme valeur.
            ->setParameter('search','%'.$search.'%')
            ->getQuery('');//on récupère notre requête.

        return $query->getResult();//on retourne le résultat de cette dernière.
    }


    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
