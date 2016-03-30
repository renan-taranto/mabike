<?php
namespace Rtaranto\Application\Factory;

interface AuthenticationTokenFactory
{
    public function create($keyLength, $minutesBeforeExpiration);
}
