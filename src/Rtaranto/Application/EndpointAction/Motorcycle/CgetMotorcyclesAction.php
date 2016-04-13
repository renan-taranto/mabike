<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\CgetActionInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetMotorcyclesAction implements CgetActionInterface
{
    private $biker;
    private $motorcycleRepository;
    private $queryParamsFetcher;
    
    public function __construct(
        Biker $biker,
        MotorcycleRepositoryInterface $motorcycleRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->biker = $biker;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet()
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->motorcycleRepository->findAllByBiker($this->biker, $filters, $orderBy, $limit, $offset);
    }
}
