<?php
namespace Rtaranto\Application\Service\Security;

interface UserRegistrationInterface
{
    public function registerUser($username, $email, $password, array $roles);
}
