<?php

namespace Bangpound\Bundle\UserBundle\Security\Core\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\AccountNotLinkedException;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends \HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $or;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    public function setRepository(ObjectRepository $or)
    {
        $this->or = $or;
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->om = $objectManager;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();

        $authMap = $this->or->findOneBy(array('resourceOwner' => $this->getProperty($response), 'id' => $username));

        if (null === $authMap || null === $username) {
            throw new AccountNotLinkedException(sprintf("User '%s' not found.", $username));
        }

        $user = $authMap->getUser();

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);

        if (!method_exists($user, 'addAuthMap')) {
            throw new \RuntimeException(sprintf("Class '%s' should have a method '%s'.", get_class($user), 'addAuthMap'));
        }

        $username = $response->getUsername();

        if (null !== $previousAuthMap = $this->or->findOneBy(array('resourceOwner' => $property, 'id' => $username))) {
            $previousUser = $previousAuthMap->getUser();
            $previousUser->removeAuthMap($previousAuthMap);

            $this->userManager->updateUser($previousUser, true);
        }

        $this->om->persist($user);
        $this->om->flush($user);

        $user->addAuthMap($property, $username);

        $this->userManager->updateUser($user, true);
    }
}
