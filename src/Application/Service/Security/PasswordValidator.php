<?php
namespace Application\Service\Security;

use Domain\Entity\User;

interface PasswordValidator
{
    public function isPasswordValid(User $user, $password);
}
