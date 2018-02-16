<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * {@inheritDoc}
 */
class PartnerUser implements UserInterface
{
    private $username;
    private $password;
    private $salt;
    private $roles;
    
    public function __construct($username, $password, $salt, array $roles = array())
    {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials()
    {
    }
    
    public function equals(UserInterface $user)
    {
        if (!$user instanceof PartnerUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
