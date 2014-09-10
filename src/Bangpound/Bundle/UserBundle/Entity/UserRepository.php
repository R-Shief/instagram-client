<?php

namespace Bangpound\Bundle\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        if (FALSE) {

        } else {
            return parent::findOneBy($criteria, $orderBy);
        }
    }

}
