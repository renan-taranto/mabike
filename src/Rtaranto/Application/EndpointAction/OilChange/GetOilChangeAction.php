<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\GetSubresourceActionInterface;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;

class GetOilChangeAction implements GetSubresourceActionInterface
{
    private $maintenancePerformerRepository;
    
    public function __construct(MaintenancePerformerRepositoryInterface $maintenancePerformerRepository)
    {
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
    }
    
    public function get($parentResourceId, $resourceId)
    {
        return $this->maintenancePerformerRepository
            ->getPerformedOilChangeByMotorcycleAndId($parentResourceId, $resourceId);
    }
}
