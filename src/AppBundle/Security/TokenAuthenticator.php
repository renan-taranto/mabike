<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $router;
    
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if (!$token = $request->headers->get('X-AUTH-TOKEN')) {
            return;
        }

        return array(
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiKey = $credentials['token'];

        $user = $this->em->getRepository('AppBundle:User')
            ->findOneBy(array('apiKey' => $apiKey));
        /*
         * When this method returns null, an UsernameNotFoundException will be
         * thrown and onAuthenticationFailure method will "catch" it somehow
         */
        if (empty($user)) {
            return;
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (in_array('ROLE_DEV', $user->getRoles())) {
            return true;
        }
        
        /*
         * When this method returns null, a BadCredentialsException will be
         * thrown and onAuthenticationFailure method will "catch" it somehow
         */
        if (
            $user instanceof User and
            $user->getApiKeyExpirationTime() < new DateTime('now')
        ) {
            return;
        }
        
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($exception instanceof UsernameNotFoundException) {
            $data = array(
                'code' => 403,
                'message' => 'Invalid authentication token.',
                'login_url' => $this->router->generate('api_v1_login')
            );
            return new JsonResponse($data, 403);
        }
        
        /*
         * When token has expired
         */
        if ($exception instanceof BadCredentialsException) {
            $data = array(
                'code' => 403,
                'message' => 'Authentication token has expired.',
                'login_url' => $this->router->generate('api_v1_login')
            );
            return new JsonResponse($data, 403);
        }
        
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        );
        return new JsonResponse($data, 403);

    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            'code' => 401,
            'message' => 'Authentication token required.',
            'login_url' => $this->router->generate('api_v1_login')
        );

        return new JsonResponse($data, 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}