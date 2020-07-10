<?php

namespace App\Repository;

use App\Entity\PitchStage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PitchStage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PitchStage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PitchStage[]    findAll()
 * @method PitchStage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PitchStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PitchStage::class);
    }

    // /**
    //  * @return PitchStage[] Returns an array of PitchStage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PitchStage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
