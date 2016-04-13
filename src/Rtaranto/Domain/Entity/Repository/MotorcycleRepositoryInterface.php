<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;

interface MotorcycleRepositoryInterface
{
    public function get($id);
    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function add(Motorcycle $motorcycle);
    public function update(Motorcycle $motorcycle);
    public function delete($id);
    public function findOneByBikerAndId(Biker $biker, $id);
    public function findAllByBiker(
        Biker $biker,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
