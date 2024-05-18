<?php

namespace App\Repository;

use App\Entity\Scores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Scores>
 *
 * @method Scores|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scores|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scores[]    findAll()
 * @method Scores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scores::class);
    }

    //    /**
    //     * @return Scores[] Returns an array of Scores objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Scores
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * @return Scores[] Returns an array of the top players. sum of games and numbers of games played by a player
     */
    public function findTopPlayers(): array
    {
        return $this->createQueryBuilder('s')
            //    ->andWhere('s.exampleField = :val')
            //    ->setParameter('val', $value)
            ->select('u.username, SUM(s.points) AS total, count(s.user) AS games')
            ->leftJoin('s.user', 'u')
            ->orderBy('total', 'DESC')
            ->groupBy('s.user')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    //// Sélection des scores additionnés des gros joueurs (limit 10)
    // SELECT username, SUM(points) as total, count(user_id) as games 
    // FROM `scores` LEFT JOIN `user` ON user.id = scores.user_id 
    // GROUP BY user_id ORDER BY total DESC 
    // LIMIT 10
}
