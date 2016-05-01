<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\CgetActionInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class CgetMotorcyclesAction implements CgetActionInterface
{
    private $biker;
    private $motorcycleRepository;
    
    public function __construct(
        Biker $biker,
        MotorcycleRepositoryInterface $motorcycleRepository
    ) {
        $this->biker = $biker;
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    public function cGet($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        return $this->motorcycleRepository->findAllByBiker($this->biker, $filters, $orderBy, $limit, $offset);
    }
}
