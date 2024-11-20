<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Member;

/**
 * @extends ServiceEntityRepository<Fruit>
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function findAll(): array
    {
        //  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        return $this->findBy(
                []
        );
    }

    /**
     * @return [Fruit][]
     */
    public function findMemberFruit(Member $member): array
    {
            return $this->createQueryBuilder('o')
                    ->leftJoin('o.cuisine', 'i')
                    ->andWhere('i.owner = :member')
                    ->setParameter('member', $member)
                    ->getQuery()
                    ->getResult()
            ;
    }

    //    /**
    //     * @return Fruit[] Returns an array of Fruit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Fruit
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
