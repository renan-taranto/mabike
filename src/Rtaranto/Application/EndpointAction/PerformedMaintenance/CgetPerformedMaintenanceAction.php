<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetPerformedMaintenanceAction extends CgetSubResourceAction
{
    private $performedMaintenanceRepository;
  
    public function __construct(
        QueryParamsFetcherInterface $queryParamsFetcher,
         PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository
    ) {
        parent::__construct($queryParamsFetcher);
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
    }
    
    protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset)
    {
        return $this->performedMaintenanceRepository->
            findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }

}
