<?php

namespace Stadline\WSSESecurityBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Exception;
use Stadline\WSSESecurityBundle\Entity\Partner;
use Stadline\WSSESecurityBundle\Entity\PartnerRepository;
use Stadline\WSSESecurityBundle\Security\User\PartnerManagerInterface;

class PartnerManager implements PartnerManagerInterface
{

    /**
     * @var Registry
     * 
     */
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return PartnerRepository
     */
    public function getRepository()
    {
        return $this->doctrine->getManager()->getRepository('StadlineWSSESecurityBundle:Partner');
    }

    /**
     * Retrieve partner with the login
     * @param  string     $login
     * @return Partner
     * @throws Exception
     */
    public function findByLogin($login)
    {
        $query = $this->getRepository()->findByLogin($login)->getQuery();

        return $query->getSingleResult();
    }

    public function createNewPartner($name, $login, $role='ROLE_API')
    {
        //If it exist
        if($this->getRepository()->findOneBy(array('login' => $login))) {
            return false;
        }
        
        $partner = new Partner();
        $partner->setLogin($login);
        $partner->setName($name);
        
        $secret = md5($login.$name.time());
        
        $partner->setSecret($secret);
        $partner->setRole($role);
        
        $this->doctrine->getManager()->persist($partner);
        $this->doctrine->getManager()->flush();
        
        return $partner;
    }

    /**
     * Renew the secret for a Login
     * @param string $login
     * @return Partner|null
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function renewSecret($login)
    {
        $partner = $this->getRepository()->findOneBy(array('login' => $login));
        /* @var $partner Partner */
        if($partner) {
            $secret = md5($partner->getLogin().$partner->getName().time());

            $partner->setSecret($secret);

            $this->doctrine->getManager()->persist($partner);
            $this->doctrine->getManager()->flush();
        }
        
        return $partner;
    }

}
