<?php
namespace Rtaranto\Domain\Entity\Repository;

interface MaintenanceWarningObserverRepositoryInterface
{
    public function findOneByMotorcycle($motorcycle);
}
