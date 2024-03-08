<?php

namespace App\Repository;

use App\Entity\transactions\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByAccountNumber(string $string=null, $date1 = null, $date2 = null)
    {
        $query= $this->createQueryBuilder('t');
        if($string!=null) {
            $query->andWhere('t.account_number = :val')
                ->setParameter('val', $string);

        }
        if ($date1 != null) {
            $query->andWhere('t.created_at > :date1')
                ->setParameter('date1', $date1);
        }
        if (($date2 != null)) {
            if (($date1 == null) or ($date2 > $date1)) {
                $query->andWhere('t.created_at < :date2')
                    ->setParameter('date2', $date2);
            }

        }
        return $query->orderBy('t.created_at', 'DESC')
            ->getQuery()->getResult();
    }

    public function findByAccountNumberDesc(string $string=null)
    {
        $query= $this->createQueryBuilder('t');
        if($string!=null) {
            $query->andWhere('t.account_number = :val')
                ->setParameter('val', $string);
        }



        return $query->orderBy('t.created_at','DESC')->getQuery()->getResult();
    }

    public function findByAccountNumberINC(string $string=null)
    {
        $query= $this->createQueryBuilder('t');
        if($string!=null) {
            $query->andWhere('t.account_number = :val')
                ->setParameter('val', $string);
        }



        return $query->orderBy('t.created_at')->getQuery()->getResult();
    }

    public function findListById($id=null): array
    {
        $req=$this->createQueryBuilder('t');
        if($id != null) {

            $req->andWhere('t.idCompte = :val')
                ->setParameter('val', $id);
        }

        return $req->getQuery()->getResult();
    }


}
