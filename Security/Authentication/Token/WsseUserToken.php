<?php 

namespace Stadline\WSSESecurityBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * {@inheritDoc}
 */
class WsseUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $roles = array())
    {
        parent::__construct($roles);
        
        // Si l'utilisateur a des rôles, on le considère comme authentifié
        if (count($roles) > 0) {
            $this->setAuthenticated(true);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCredentials()
    {
        return $this->getRoles();
    }
}
