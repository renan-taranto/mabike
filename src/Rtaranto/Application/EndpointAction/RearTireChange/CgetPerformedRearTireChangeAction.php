<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetPerformedRearTireChangeAction extends CgetSubResourceAction
{
    private $performedRearTireChangeRepository;
    
    public function __construct(
        PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        parent::__construct($queryParamsFetcher);
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
    }
    
    protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset)
    {
        return $this->performedRearTireChangeRepository
            ->findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}
