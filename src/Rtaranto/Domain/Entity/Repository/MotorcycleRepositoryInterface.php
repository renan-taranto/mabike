<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Motorcycle;
use Symfony\Component\Security\Core\User\UserInterface;

interface MotorcycleRepositoryInterface
{
    public function get($id);
    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function add(Motorcycle $motorcycle);
    public function update(Motorcycle $motorcycle);
    public function delete($id);
    public function findAllByUser(
        UserInterface $user,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
