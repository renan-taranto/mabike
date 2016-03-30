<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Domain\Entity\User;

interface TokenGeneratorInterface
{
    public function createTokenForUser(User $user);
}
