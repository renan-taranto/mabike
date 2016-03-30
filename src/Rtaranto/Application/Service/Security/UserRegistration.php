<?php
namespace Rtaranto\Application\Service\Security;

interface UserRegistration
{
    public function registerUser($username, $email, $password);
}
