<?php
namespace Application\Service\Security;

interface UserRegistration
{
    public function registerUser($username, $email, $password);
}
