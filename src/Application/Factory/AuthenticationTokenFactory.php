<?php
namespace Application\Factory;

interface AuthenticationTokenFactory
{
    public function create($keyLength, $minutesBeforeExpiration);
}
