<?php

namespace App\Repository;

use App\Entity\CategoryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategoryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryCategory[]    findAll()
 * @method CategoryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategoryCategory::class);
    }

    // /**
    //  * @return CategoryCategory[] Returns an array of CategoryCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
