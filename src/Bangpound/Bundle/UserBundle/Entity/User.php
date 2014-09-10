<?php

namespace Bangpound\Bundle\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity("UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AuthMap", mappedBy="user", cascade={"ALL"}, indexBy="resourceOwner")
     */
    protected $authMap;

    public function addAuthMap($resourceOwner, $id)
    {
        $this->authMap[$resourceOwner] = new AuthMap($resourceOwner, $id, $this);
    }

    public function removeAuthMap(AuthMap $authMap)
    {
        $this->authMap->removeElement($authMap);
    }
}
