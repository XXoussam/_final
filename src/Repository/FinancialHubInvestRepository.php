<?php

namespace App\Repository;

use App\Entity\FinancialHubInvest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FinancialHubInvest>
 *
 * @method FinancialHubInvest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinancialHubInvest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinancialHubInvest[]    findAll()
 * @method FinancialHubInvest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialHubInvestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinancialHubInvest::class);
    }

//    /**
//     * @return FinancialHubInvest[] Returns an array of FinancialHubInvest objects
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

//    public function findOneBySomeField($value): ?FinancialHubInvest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
