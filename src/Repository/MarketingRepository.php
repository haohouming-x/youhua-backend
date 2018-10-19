<?php

namespace App\Repository;

use App\Entity\Marketing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Marketing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Marketing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Marketing[]    findAll()
 * @method Marketing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Marketing::class);
    }

//    /**
//     * @return Marketing[] Returns an array of Marketing objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Marketing
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
