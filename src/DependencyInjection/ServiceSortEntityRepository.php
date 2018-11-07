<?php

namespace App\DependencyInjection;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Picoss\SonataExtraAdminBundle\Entity\Repository\SortableRepository;
// use Gedmo\Sortable\Entity\Repository\SortableRepository;


/**
 * Optional EntityRepository base class with a simplified constructor (for autowiring).
 *
 * To use in your class, inject the "registry" service and call
 * the parent constructor. For example:
 *
 * class YourEntityRepository extends ServiceSortEntityRepository
 * {
 *     public function __construct(RegistryInterface $registry)
 *     {
 *         parent::__construct($registry, YourEntity::class);
 *     }
 * }
 */
class ServiceSortEntityRepository extends SortableRepository implements ServiceEntityRepositoryInterface
{
    /**
     * @param string $entityClass The class name of the entity this repository manages
     */
    public function __construct(ManagerRegistry $registry, $entityClass)
    {
        $manager = $registry->getManagerForClass($entityClass);

        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
