<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

abstract class CgetSubResourceAction implements CgetSubresourceActionInterface
{
    private $queryParamsFetcher;
    
    public function __construct(QueryParamsFetcherInterface $queryParamsFetcher)
    {
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet($parentResourceId)
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
    
    abstract protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset);
}
