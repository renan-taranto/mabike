<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetPerformedFrontTireChangeAction extends CgetSubResourceAction
{
    private $performedFrontTireChangeRepository;
    
    public function __construct(
        PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        parent::__construct($queryParamsFetcher);
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
    }
    
    protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset)
    {
        return $this->performedFrontTireChangeRepository
            ->findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}
