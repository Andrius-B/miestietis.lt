<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 15.11.8
 * Time: 09.37
 */

namespace Miestietis\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    public function findOneBy(array $criteria)
    {
        foreach ($criteria as $key => $value){
            return $this->getEntityManager()
                ->createQuery('SELECT usr
            FROM MiestietisMainBundle:User usr
            WHERE usr.'.$key.' = '.$value)
                ->getResult()[0];
        }

    }
}




