<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     *
     * Requête qui me permet de récupérer les produtis en fonction de la recherche de l'utilisateur
     * @return Product[] 
     */
    public function findWidthSearch(Search $search)
    {
        $query = $this
            ->createQueryBuilder('p') // mapping avec la table Product ='p'
            ->select('c', 'p')  // Sélectionne dans cette Query : Category (c) et Product (p)
            ->join('p.category', 'c'); // Faire une jointure entre catégorie du produit et la table produit

        if (!empty($search->categories)) { // Si tu n'es pas vide 
            $query = $query // Reprendre la query créé 
                ->andWhere('c.id IN (:categories)') // Les id des catégories sont dans la liste catégorie envoyé en paramètre
                ->setParameter('categories', $search->categories); // Ajout du paramètre categories
        }

        if (!empty($search->string)) { // Gère le champ recherche
            $query = $query
                ->andWhere('p.name LIKE :string') // Si un nom de produit match avec le champ recherche rempli
                ->setParameter('string', "%{$search->string}%"); // Permet la recherche partielle
        }

        return $query->getQuery()->getResult(); // Retourne le résultat
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
