<?php
namespace Application\Service\Security;

interface LoginService
{
    public function login($username, $password);
}
