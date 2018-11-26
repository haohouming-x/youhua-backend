<?php

namespace App\Repository;

use App\Entity\Wechat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Wechat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wechat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wechat[]    findAll()
 * @method Wechat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WechatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wechat::class);
    }

    // /**
    //  * @return Wechat[] Returns an array of Wechat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wechat
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
