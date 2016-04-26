<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class DeletePerformedMaintenanceAction extends DeleteSubResourceAction
{
    private $motorcycleRepository;
    private $maintenanceRepository;
    private $performedMaintenanceRepository;
    
    public function __construct(
        MotorcycleRepositoryInterface $motorcycleRepository,
        MaintenanceRepositoryInterface $maintenanceRepository,
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository
    ) {
        $this->motorcycleRepository = $motorcycleRepository;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
    }
    
    protected function deleteSubResource($parentResourceId, $subResource)
    {
        /* @var $maintenance Maintenance */
        $maintenance = $this->maintenanceRepository->findOneByMotorcycle($parentResourceId);
        $maintenance->removePerformedMaintenance($subResource);
        $this->maintenanceRepository->update($maintenance);
        $this->notifyWarningsObservers($parentResourceId);
    }

    protected function findSubResourceByParentResource($parentResourecId, $subResourceId)
    {
        return $this->performedMaintenanceRepository->findOneByMotorcycleAndId($parentResourecId, $subResourceId);
    }
    
    private function notifyWarningsObservers($motorcycleId)
    {
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->motorcycleRepository->get($motorcycleId);
        $motorcycle->notifyMaintenanceWarningObservers();
        $this->motorcycleRepository->update($motorcycle);
    }

}
