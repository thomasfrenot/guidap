<?php

namespace App\Repository;

use App\Entity\RecreationPark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    /**
     * Get recreation park by page of limited results
     * @param $page
     * @param $maxResults
     * @return int|mixed|string
     */
    public function findWithSearchAndPaginator( $page, $limit = 2, $search = null, $filterActivities = []): Paginator
    {
        // 10 results by page
        $query = $this->createQueryBuilder('rp')
            ->setFirstResult(($page) * $limit)
            ->setMaxResults($limit);

        if (null !== $search) {
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('upper(rp.name)', 'upper(:search)'),
                $query->expr()->like('rp.slug', ':search'),
                $query->expr()->like('upper(rp.description)', 'upper(:search)')
            ))
                ->setParameter('search', '%'.$search.'%');
        }

        if (0 < count($filterActivities)) {
            $query
                ->innerJoin('rp.activities', 'a')
                ->andWhere($query->expr()->orX(
                    'a.slug IN (:filterActivities)',
                    'a.name IN (:filterActivities)'
                ))
                ->setParameter('filterActivities', $filterActivities);
        }

        return new Paginator($query);
    }
}
