<?php
namespace Rtaranto\Domain\Entity\Repository;

interface PerformedMaintenanceRepositoryInterface
{
    public function update($performedMaintenance);
    public function delete($performedMaintenance);
    public function findOneByMotorcycleAndId($motorcycle, $performedMaintenanceId);
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
