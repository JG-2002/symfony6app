<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function rechercher($serch)
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.marque','m')
        ->leftJoin('p.category','c')
        ->andWhere('p.name LIKE :serch OR c.name LIKE :serch OR m.name LIKE :serch ')
        ->setParameter('serch', '%'.$serch.'%');
        return $qb->getQuery()->getResult();
    }
}
