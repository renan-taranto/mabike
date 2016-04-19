<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\CgetSubresourceActionInterface;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetPerformedOilChangeAction implements CgetSubresourceActionInterface
{
    /**
     * @var PerformedOilChangeRepositoryInterface
     */
    private $performedOilChangeRepository;
    
    /**
     * @var QueryParamsFetcherInterface
     */
    private $queryParamsFetcher;
    
    /**
     * @param PerformedOilChangeRepositoryInterface $performedOilChangeRepository
     * @param QueryParamsFetcherInterface $queryParamsFetcher
     */
    public function __construct(
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet($parentResourceId)
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->performedOilChangeRepository
            ->findAllByMotorcycle($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
}
