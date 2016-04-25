<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class GetPerformedMaintenanceAction extends GetSubResourceAction
{
    private $performedMaintenanceRepository;
    
    public function __construct(PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository)
    {
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
    }

    protected function findSubResourceByParentResource($parentResource, $subResourceId)
    {
        return $this->performedMaintenanceRepository->findOneByMotorcycleAndId($parentResource, $subResourceId);
    }

}
