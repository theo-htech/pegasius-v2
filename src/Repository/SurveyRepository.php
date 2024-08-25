<?php

namespace App\Repository;

use App\Entity\Survey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Survey>
 *
 * @method Survey|null find($id, $lockMode = null, $lockVersion = null)
 * @method Survey|null findOneBy(array $criteria, array $orderBy = null)
 * @method Survey[]    findAll()
 * @method Survey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survey::class);
    }

//    /**
//     * @return Survey[] Returns an array of Survey objects
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

//    public function findOneBySomeField($value): ?Survey
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * @param $user
     * @return array
     */
    public function findAllfromUser($user): array {
        $qb = $this->createQueryBuilder('s');
        $qb->andWhere('s.creator = :user')
                ->setParameter('user', $user);
        return $qb->getQuery()->getResult();
    }

    /**
     * @extends ServiceEntityRepository<Survey>
     *
     * @method Survey[] findAllfromUserWhoHaveAdminAccess()
     *
     * Returns an array of Survey entities where the user who created the survey has admin access.
     * The surveys are ordered by the last admin access in descending order.
     *
     * @return Survey[] An array of Survey entities
     */
    public function findAllfromUserWhoHaveAdminAccess(): array {
        $qb = $this->createQueryBuilder('s');
        $qb->Join('s.creator', 'u')
            ->andWhere('u.adminCanSee = true')
            ->andWhere('u.lastAdminAccess IS NOT NULL')
            ->orWhere('u.isAdmin = true')
            ->orWhere('s.status = :status')
            ->setParameter('status', Survey::STATUS_NEW)
            ->orderBy('u.lastAdminAccess', 'DESC');
        return $qb->getQuery()->getResult();
    }

    /**
     * Saves a survey entity.
     *
     * @param Survey $survey The survey entity to be saved.
     *
     * @return void
     */
    public function save($survey) {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($survey);
        $entityManager->flush();
    }
}
