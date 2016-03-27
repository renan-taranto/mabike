<?php
namespace Application\Service\Security;

use Domain\Entity\User;

interface UserTokenGenerator
{
    public function createTokenForUser(User $user);
}
