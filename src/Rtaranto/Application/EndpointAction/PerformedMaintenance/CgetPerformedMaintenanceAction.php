<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class CgetPerformedMaintenanceAction extends CgetSubResourceAction
{
    public function __construct(PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository)
    {
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
    }
    
    protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset)
    {
        return $this->performedMaintenanceRepository->
            findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }

}
