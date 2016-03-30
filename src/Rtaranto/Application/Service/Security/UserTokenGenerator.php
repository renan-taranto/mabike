<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Domain\Entity\User;

interface UserTokenGenerator
{
    public function createTokenForUser(User $user);
}
