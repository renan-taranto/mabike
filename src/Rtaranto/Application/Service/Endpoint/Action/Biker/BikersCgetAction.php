<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class BikersCgetAction implements BikersCgetActionInterface
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
    
    public function getAll()
    {
        $filters = $this->queryParamsFetcher->getParam('filters', true, null);
        $orderBy = $this->queryParamsFetcher->getParam('orderBy', true, null);
        $limit = $this->queryParamsFetcher->getParam('limit');
        $offset = $this->queryParamsFetcher->getParam('offset');
        return $this->bikerRepository->getAll($filters, $orderBy, $limit, $offset);
    }
}
