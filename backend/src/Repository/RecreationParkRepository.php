<?php

namespace App\Repository;

use App\Entity\RecreationPark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecreationPark>
 *
 * @method RecreationPark|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecreationPark|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecreationPark[]    findAll()
 * @method RecreationPark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecreationParkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecreationPark::class);
    }

    public function add(RecreationPark $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RecreationPark $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
