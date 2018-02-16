<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

use Stadline\WSSESecurityBundle\Entity\Partner;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * {@inheritDoc}
 */
class PartnerUserProvider implements UserProviderInterface
{
    /**
     * @var PartnerManagerInterface
     */
    protected $userManager;

    /**
     * Constructor.
     *
     * @param PartnerManagerInterface $userManager
     */
    public function __construct(PartnerManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $partner = $this->userManager->findByLogin($username);
            /* @var $partner Partner*/
            $partnerUser = new PartnerUser($username, $partner->getSecret(), 'salt', array($partner->getRole()));
            
            return $partnerUser;
            
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
    }

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof PartnerUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Stadline\WSSESecurityBundle\Security\User\PartnerUser';
    }
}
