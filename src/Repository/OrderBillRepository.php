<?php

namespace App\Repository;

use App\Entity\OrderBill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderBill|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderBill|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderBill[]    findAll()
 * @method OrderBill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderBillRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderBill::class);
    }

//    /**
//     * @return OrderBill[] Returns an array of OrderBill objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderBill
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
