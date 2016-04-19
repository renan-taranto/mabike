<?php
namespace Rtaranto\Domain\Entity\Repository;

interface MaintenanceRepositoryInterface
{
    public function findOneByMotorcycle($motorcycle);
}
