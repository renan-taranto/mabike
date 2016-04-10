<?php
namespace Rtaranto\Application\Service\Security\Factory;

interface AuthenticationTokenFactoryInterface
{
    public function create($keyLength, $minutesBeforeExpiration);
}
