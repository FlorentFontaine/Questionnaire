<?php

namespace App\Repository;

use App\Entity\TextQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|TextQuestion find($id, $lockMode = null, $lockVersion = null)
 * @method null|TextQuestion findOneBy(array $criteria, array $orderBy = null)
 * @method TextQuestion[]    findAll()
 * @method TextQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextQuestion::class);
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
