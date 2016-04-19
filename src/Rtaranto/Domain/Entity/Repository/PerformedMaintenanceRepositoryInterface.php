<?php
namespace Rtaranto\Domain\Entity\Repository;

interface PerformedMaintenanceRepositoryInterface
{
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
