<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Domain\Entity\User;

interface PasswordValidator
{
    public function isPasswordValid(User $user, $password);
}
