<?php
namespace Domain\Entity\Factory;

interface UserFactory
{
    public function createUser($username, $email, $password);
}
