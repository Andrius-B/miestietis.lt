<?php

namespace Miestietis\MainBundle\Repository;
use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    public function findOneBy(array $criteria)
    {
        $connection = $this->getEntityManager()->getConnection();
        foreach ($criteria as $key => $value) {
            $usr = $this->getEntityManager()
                ->createQuery('SELECT usr
            FROM MiestietisMainBundle:User usr
            WHERE usr.'.$connection->quote($key).' = '.$connection->quote($value)) //escape to avoid sql injects and make scrutinizer happy
                ->getResult();
            if (isset($usr[0])) {
                return $usr[0];
            } else {
                return null;
            }
        }
    }

}




