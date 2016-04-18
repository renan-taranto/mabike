<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\CgetSubresourceActionInterface;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetOilChangeAction implements CgetSubresourceActionInterface
{
    /**
     * @var MaintenancePerformerRepositoryInterface
     */
    private $maintenancePerformerRepository;
    
    /**
     * @var QueryParamsFetcherInterface
     */
    private $queryParamsFetcher;
    
    /**
     * @param MaintenancePerformerRepositoryInterface $maintenancePerformerRepository
     * @param QueryParamsFetcherInterface $queryParamsFetcher
     */
    public function __construct(
        MaintenancePerformerRepositoryInterface $maintenancePerformerRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet($parentResourceId)
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->maintenancePerformerRepository->
            findAllPerformedOilChangesByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}
