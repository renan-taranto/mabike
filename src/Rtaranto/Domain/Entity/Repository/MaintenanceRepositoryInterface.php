<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\PerformedMaintenance;

interface MaintenanceRepositoryInterface
{
    public function update(Maintenance $maintenance);
    public function findOneByMotorcycle($motorcycle);
    public function findOneByPerformedMaintenance(PerformedMaintenance $performedMaintenance);
}
