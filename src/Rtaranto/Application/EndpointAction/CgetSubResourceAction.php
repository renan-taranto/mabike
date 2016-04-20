<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetSubResourceAction implements CgetSubresourceActionInterface
{
    private $subResourceRepository;
    
    private $queryParamsFetcher;
    
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->subResourceRepository = $subResourceRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet($parentResourceId)
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->subResourceRepository
            ->findAllByParentResource($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}
