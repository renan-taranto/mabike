<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\CgetActionInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CgetMotorcyclesAction implements CgetActionInterface
{
    private $user;
    private $motorcycleRepository;
    private $queryParamsFetcher;
    
    public function __construct(
        UserInterface $user,
        MotorcycleRepositoryInterface $motorcycleRepository,
        QueryParamsFetcherInterface $queryParamsFetcher
    ) {
        $this->user = $user;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->queryParamsFetcher = $queryParamsFetcher;
    }
    
    public function cGet()
    {
        $filters = $this->queryParamsFetcher->getFiltersParam();
        $orderBy = $this->queryParamsFetcher->getOrderByParam();
        $limit = $this->queryParamsFetcher->getLimitParam();
        $offset = $this->queryParamsFetcher->getOffsetParam();
        
        return $this->motorcycleRepository->findAllByUser($this->user, $filters, $orderBy, $limit, $offset);
    }
}
