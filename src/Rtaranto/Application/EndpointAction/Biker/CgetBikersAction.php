<?php
namespace Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\CgetActionInterface;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetBikersAction implements CgetActionInterface
{
    private $bikerRepository;
    private $queryParamsFetcher;
    
    public function __construct(
        BikerRepositoryInterface $bikerRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->bikerRepository = $bikerRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet()
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        return $this->bikerRepository->getAll($filters, $orderBy, $limit, $offset);
    }
}
