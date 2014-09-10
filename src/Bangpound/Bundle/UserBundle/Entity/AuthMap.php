<?php

namespace Bangpound\Bundle\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity("AuthMapRepository")
 * @ORM\Table("auth_map", uniqueConstraints={@ORM\UniqueConstraint(columns={"resource_owner", "id"})})})
 * Class AuthMap
 * @package Bangpound\Bundle\UserBundle\Entity
 */
class AuthMap
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="authMap")
     * @var User
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\Column("resource_owner", type="string")
     */
    protected $resourceOwner;

    /**
     * @ORM\Column("id", type="string")
     */
    protected $id;

    public function __construct($resourceOwner, $id, $user)
    {
        $this->resourceOwner = $resourceOwner;
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
}
