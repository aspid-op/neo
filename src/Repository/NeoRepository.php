<?php

namespace App\Repository;

use App\Entity\Neo;
use App\Entity\NeoInterface;
use App\Query\FindNeoQueryInterface;
use App\Tools\Paginator\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

class NeoRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Paginator $paginator,
    ) {
        parent::__construct($registry, Neo::class);
    }

    public function save(NeoInterface $neo): void
    {
        $this->getEntityManager()->persist($neo);
        $this->getEntityManager()->flush();
    }


    public function bulkSave(array $data): void
    {
        foreach ($data as $neo) {
            $this->getEntityManager()->persist($neo);
        }

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    public function findAllByQuery(FindNeoQueryInterface $findNeoQuery): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('n');

        if ($findNeoQuery->getId()) {
            $queryBuilder
                ->andWhere('n.id = :id')
                ->setParameter('id', $findNeoQuery->getId())
            ;
        }

        if ($findNeoQuery->getNeoReferenceId()) {
            $queryBuilder
                ->andWhere('n.neoReferenceId = :neoReferenceId')
                ->setParameter('neoReferenceId', $findNeoQuery->getNeoReferenceId())
            ;
       }

        if ($findNeoQuery->getName()) {
            $queryBuilder
                ->andWhere('n.name = :name')
                ->setParameter('name', $findNeoQuery->getName())
            ;
        }

        if ($findNeoQuery->getDateFrom()) {
            $queryBuilder
                ->andWhere('n.date >= :dateFrom')
                ->setParameter('dateFrom', $findNeoQuery->getDateFrom())
            ;
        }

        if ($findNeoQuery->getDateTo()) {
            $queryBuilder
                ->andWhere('n.date <= :dateTo')
                ->setParameter('dateTo', $findNeoQuery->getDateTo())
            ;
        }

        if ($findNeoQuery->getSpeedFrom()) {
            $queryBuilder
                ->andWhere('n.speed >= :speedFrom')
                ->setParameter('speedFrom', $findNeoQuery->getSpeedFrom())
            ;
        }

        if ($findNeoQuery->getSpeedTo()) {
            $queryBuilder
                ->andWhere('n.speed <= :speedTo')
                ->setParameter('speedTo', $findNeoQuery->getSpeedTo())
            ;
        }

        if ($findNeoQuery->getIsHazardous()) {
            $queryBuilder
                ->andWhere('n.isHazardous = :isHazardous')
                ->setParameter('isHazardous', $findNeoQuery->getIsHazardous())
            ;
        }

        return $this->paginator->paginate($queryBuilder->getQuery(), $findNeoQuery->getPaginatorControlElement());
    }

    public function findByHazardous(FindNeoQueryInterface $findNeoQuery): Paginator
    {
        $query = $this
            ->createQueryBuilder('n')
            ->andWhere('n.isHazardous = :isHazardous')
            ->setParameter('isHazardous', true)
            ->getQuery()
        ;

        return $this->paginator->paginate($query, $findNeoQuery->getPaginatorControlElement());
    }

    public function findFastest(FindNeoQueryInterface $findNeoQuery): ?NeoInterface
    {
        $queryBuilder = $this->createQueryBuilder('n');

        if ($findNeoQuery->getIsHazardous()) {
            $queryBuilder
                ->andWhere('n.isHazardous = :isHazardous')
                ->setParameter('isHazardous', $findNeoQuery->getIsHazardous())
            ;
        }

        return $queryBuilder
            ->setMaxResults(1)
            ->orderBy('n.speed', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findBestMonth(FindNeoQueryInterface $findNeoQuery)
    {
        $queryBuilder = $this->createQueryBuilder('n')
            ->select('COUNT(n) total, MONTH(n.date) month, YEAR(n.date) year')
        ;

        if ($findNeoQuery->getIsHazardous()) {
            $queryBuilder
                ->andWhere('n.isHazardous = :isHazardous')
                ->setParameter('isHazardous', $findNeoQuery->getIsHazardous())
            ;
        }

        return $queryBuilder
            ->andWhere('YEAR(n.date) = :year')
            ->setParameter('year', date('Y'))
            ->groupBy('month, year')
            ->orderBy('total', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY)
        ;
    }
}
