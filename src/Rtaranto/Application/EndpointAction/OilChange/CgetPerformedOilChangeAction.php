<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetPerformedOilChangeAction extends CgetSubResourceAction
{
    private $performedOilChangeRepository;
    
    public function __construct(
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        parent::__construct($queryParamsFetcher);
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset)
    {
        return $this->performedOilChangeRepository
            ->findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}

