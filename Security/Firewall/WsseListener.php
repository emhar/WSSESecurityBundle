<?php 

namespace Stadline\WSSESecurityBundle\Security\Firewall;

use Stadline\WSSESecurityBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * {@inheritDoc}
 */
class WsseListener implements ListenerInterface
{
    /**
     * @var SecurityContextInterface|TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;
    
    public function __construct($tokenStorage, AuthenticationManagerInterface $authenticationManager) {
        if (!$tokenStorage instanceof SecurityContextInterface && !$tokenStorage instanceof TokenStorageInterface) {
            throw new \InvalidArgumentException(sprintf('The first argument should be an instance of TokenStorageInterface or SecurityContextInterface, "%s" given.', is_object($tokenStorage) ? get_class($tokenStorage) : gettype($tokenStorage)));
        }
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    /**
     * {@inheritDoc}
     * @throws \InvalidArgumentException
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        //WSSE Part
        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if (!$request->headers->has('x-wsse') ||
            1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)
        ) {
            
            // no x-wsse or false x-wsse, deny authentication with a '401 Unauthorized'
            $response = new Response();
            $response->setStatusCode(401);
            $event->setResponse($response);
            return;
        }

        $token = new WsseUserToken();
        $token->setUser($matches[1]);
        
        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];
        
        try {
            $authToken = $this->authenticationManager->authenticate($token);
        
            $this->tokenStorage->setToken($authToken);
        } catch (AuthenticationException $failed) {
      
            // Deny authentication with a '403 Forbidden' HTTP response
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
        
        }
    }
}
