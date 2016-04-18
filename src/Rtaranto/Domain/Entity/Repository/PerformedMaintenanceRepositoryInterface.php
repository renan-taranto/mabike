<?php
namespace Rtaranto\Domain\Entity\Repository;

interface PerformedMaintenanceRepositoryInterface
{
    public function findAllByMaintenance(
        $maintenance,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
    
    public function getByMaintenanceAndId($maintenance, $performedMaintenanceId);
}
