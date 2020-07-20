<?php

namespace App\Repository;

use App\Entity\MCQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|MCQuestion find($id, $lockMode = null, $lockVersion = null)
 * @method null|MCQuestion findOneBy(array $criteria, array $orderBy = null)
 * @method MCQuestion[]    findAll()
 * @method MCQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MCQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MCQuestion::class);
    }

    public function findByIdCategory($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.category = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function countByCategory($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.category = :val')
            ->setParameter('val', $value)
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
