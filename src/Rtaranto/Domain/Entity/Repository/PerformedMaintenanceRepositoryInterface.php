<?php
namespace Rtaranto\Domain\Entity\Repository;

interface PerformedMaintenanceRepositoryInterface
{
    public function findAllByMaintenance(
            $maintenance,
            $filters,
            $orderBy,
            $limit,
            $offset
    );
}
