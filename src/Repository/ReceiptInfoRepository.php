<?php

namespace App\Repository;

use App\Entity\ReceiptInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReceiptInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceiptInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceiptInfo[]    findAll()
 * @method ReceiptInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReceiptInfo::class);
    }

//    /**
//     * @return ReceiptInfo[] Returns an array of ReceiptInfo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReceiptInfo
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
