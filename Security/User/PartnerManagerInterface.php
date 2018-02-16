<?php 

namespace Stadline\WSSESecurityBundle\Security\User;

use Stadline\WSSESecurityBundle\Entity\Partner;

interface PartnerManagerInterface
{
    /**
     * @param string $login
     *
     * @return Partner
     */
    public function findByLogin($login);
}
