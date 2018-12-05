<?php

namespace App\Repository;

use App\Entity\PostId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostId|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostId|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostId[]    findAll()
 * @method PostId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostIdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostId::class);
    }

    // /**
    //  * @return PostId[] Returns an array of PostId objects
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
    public function findOneBySomeField($value): ?PostId
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
