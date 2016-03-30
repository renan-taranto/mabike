<?php
namespace Rtaranto\Application\Factory;

interface AuthenticationTokenFactoryInterface
{
    public function create($keyLength, $minutesBeforeExpiration);
}
