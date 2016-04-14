<?php
namespace Rtaranto\Application\Service\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

interface LoginServiceInterface
{
    /**
     * @param string $username
     * @param string $password
     * @throws BadCredentialsException
     */
    public function login($username, $password);
}
