<?php

namespace App\Repository;

use App\Entity\MCAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|MCAnswer find($id, $lockMode = null, $lockVersion = null)
 * @method null|MCAnswer findOneBy(array $criteria, array $orderBy = null)
 * @method MCAnswer[]    findAll()
 * @method MCAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MCAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MCAnswer::class);
    }

    public function findMCAnswer($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findMCAnswerByQuestion($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.mCQuestion = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }
}
