<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Domain\Entity\User;

interface PasswordValidatorInterface
{
    public function isPasswordValid(User $user, $password);
}
