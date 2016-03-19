<?php
namespace Domain\Entity\Repository;

use Domain\Entity\User;

interface UserRepository
{
    public function addUser(User $user);
    public function updateUser(User $user);
    public function removeUser(User $user);
    public function findByUsername($username);
    public function findByApiKey($apiKey);
    public function query(array $valuesByFields);
}
