<?php
namespace Rtaranto\Domain\Entity\Factory;

interface UserFactoryInterface
{
    public function createUser($username, $email, $password, array $roles);
}
