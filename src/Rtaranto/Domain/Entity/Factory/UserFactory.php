<?php
namespace Rtaranto\Domain\Entity\Factory;

interface UserFactory
{
    public function createUser($username, $email, $password);
}
