<?php

namespace App\Repository;

use App\Entity\CategorieProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieProduit>
 *
 * @method CategorieProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieProduit[]    findAll()
 * @method CategorieProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieProduit::class);
    }
    public function getCategories(array $params = [])
    {
        $args =  array_merge([
            'page' => 'index'
        ], $params);

        foreach($args as $key => $val)
            ${$key} = $val;

        $qb = $this->createQueryBuilder('c');
        if($page == 'neuf' || $page == 'occasion' || $page == 'location')
        {
            $qb->join('c.produits','p');
            if($page == 'neuf' || $page == 'occasion')
            {
                $qb->andWhere('p.type = :type')
                    ->setParameter('type', $page);
            }
            if($page == 'location')
            {
                $qb->andWhere('p.location = :location')
                    ->setParameter('location', true);
            }
        }
        $qb->orderBy('c.position','ASC');
        return $qb->getQuery()->getResult();
    }
}
