<?php

namespace App\Repository;

use App\Entity\PostP2p;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostP2p>
 *
 * @method PostP2p|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostP2p|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostP2p[]    findAll()
 * @method PostP2p[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostP2pRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostP2p::class);
    }

//    /**
//     * @return PostP2p[] Returns an array of PostP2p objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostP2p
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
