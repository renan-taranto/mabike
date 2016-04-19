<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\PerformedRearTireChange;

interface PerformedRearTireChangeRepositoryInterface
{
    public function update(PerformedRearTireChange $performedRearTireChange);
    public function delete(PerformedRearTireChange $performedRearTireChange);
    public function findOneByMotorcycleAndId($motorcycle, $performedRearTireChange);
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
