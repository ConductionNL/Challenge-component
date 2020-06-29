<?php

namespace App\Repository;

use App\Entity\TenderStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TenderStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenderStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenderStage[]    findAll()
 * @method TenderStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenderStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TenderStage::class);
    }

    // /**
    //  * @return TenderStage[] Returns an array of TenderStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TenderStage
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
