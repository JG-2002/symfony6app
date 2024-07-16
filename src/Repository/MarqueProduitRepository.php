<?php

namespace App\Repository;

use App\Entity\MarqueProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MarqueProduit>
 *
 * @method MarqueProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarqueProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarqueProduit[]    findAll()
 * @method MarqueProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarqueProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarqueProduit::class);
    }

    public function getMarque(array $params = [])
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
