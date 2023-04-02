<?php

namespace App\Repository;

use App\Entity\Contatos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Contatos>
 *
 * @method Contatos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contatos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contatos[]    findAll()
 * @method Contatos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContatosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contatos::class);
    }

    public function save(Contatos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contatos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function removebyId(int $id): Void
    {
        $contato = $this->getEntityManager()->getPartialReference(Contatos::class, $id);
        $this->remove($contato, true);
    }


//    /**
//     * @return Contatos[] Returns an array of Contatos objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contatos
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
