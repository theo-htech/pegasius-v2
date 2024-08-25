<?php

namespace App\Repository;

use App\Entity\SurveyFillUpRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SurveyFillUpRequest>
 *
 * @method SurveyFillUpRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyFillUpRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyFillUpRequest[]    findAll()
 * @method SurveyFillUpRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyFillUpRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyFillUpRequest::class);
    }

    /**
     * @param $entity
     * @return void
     */
    public function save($entity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }


    public function remove($entity)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity);
        $entityManager->flush();
    }

    public function getTokenByEmailAndSurveyId($email, $surveyId) {
        return $this->createQueryBuilder('sfr')
            ->select('sfr.hashedToken')
            ->join('sfr.poll', 'p')
            ->join('p.survey', 's')
            ->andWhere('sfr.email = :email')
            ->andWhere('s.id = :surveyId')
            ->setParameter('email', $email)
            ->setParameter('surveyId', $surveyId)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return SurveyFillUpRequest[] Returns an array of SurveyFillUpRequest objects
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

//    public function findOneBySomeField($value): ?SurveyFillUpRequest
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
