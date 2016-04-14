<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Domain\Entity\User;

interface TokenGeneratorInterface
{
    /**
     * @param User $user
     * @return AuthenticationTokenDTO
     */
    public function createTokenForUser(User $user);
}
