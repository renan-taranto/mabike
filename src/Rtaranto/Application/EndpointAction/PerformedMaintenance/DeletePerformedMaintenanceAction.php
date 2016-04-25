<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class DeletePerformedMaintenanceAction extends DeleteSubResourceAction
{
    private $maintenanceRepository;
    private $performedMaintenanceRepository;
    
    public function __construct(
        MaintenanceRepositoryInterface $maintenanceRepository,
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository
    ) {
        $this->maintenanceRepository = $maintenanceRepository;
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
    }
    
    protected function deleteSubResource($parentResourceId, $subResource)
    {
        /* @var $maintenance Maintenance */
        $maintenance = $this->maintenanceRepository->findOneByMotorcycle($parentResourceId);
        $maintenance->removePerformedMaintenance($subResource);
        $this->maintenanceRepository->update($maintenance);
        
    }

    protected function findSubResourceByParentResource($parentResourecId, $subResourceId)
    {
        return $this->performedMaintenanceRepository->findOneByMotorcycleAndId($parentResourecId, $subResourceId);
    }

}
