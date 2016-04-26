<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\MaintenanceWarningObserver;

interface MaintenanceWarningObserverRepositoryInterface
{
    public function findOneByMotorcycle($motorcycle);
    public function update(MaintenanceWarningObserver $maintenanceWarningObserver);
}
