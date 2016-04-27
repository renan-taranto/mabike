<?php
namespace Rtaranto\Application\EndpointAction\Warnings;

use Rtaranto\Application\EndpointAction\GetActionInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class GetWarningsAction implements GetActionInterface
{
    private $motorcycleRepository;
    
    public function __construct(MotorcycleRepositoryInterface $motorcycleRepository)
    {
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    public function get($id)
    {
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->motorcycleRepository->get($id);
        return $motorcycle->getWarnings();
    }

}
