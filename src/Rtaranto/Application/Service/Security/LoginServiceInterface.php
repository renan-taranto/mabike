<?php
namespace Rtaranto\Application\Service\Security;

interface LoginServiceInterface
{
    public function login($username, $password);
}
