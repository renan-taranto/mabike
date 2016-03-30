<?php
namespace Rtaranto\Application\Service\Security;

interface LoginService
{
    public function login($username, $password);
}
