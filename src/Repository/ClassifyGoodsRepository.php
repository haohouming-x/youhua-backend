<?php

namespace App\Repository;

use App\Entity\ClassifyGoods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Gedmo\Sortable\Entity\Repository\SortableRepository;

/**
 * @method ClassifyGoods|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClassifyGoods|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClassifyGoods[]    findAll()
 * @method ClassifyGoods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassifyGoodsRepository extends SortableRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClassifyGoods::class);
    }

//    /**
//     * @return ClassifyGoods[] Returns an array of ClassifyGoods objects
//     */
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
    public function findOneBySomeField($value): ?ClassifyGoods
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
