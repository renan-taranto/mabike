<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\GetMotorcycleAction;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;

class GetMotorcycleActionFactory implements GetActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @return GetMotorcycleAction
     */
    public function createGetAction()
    {
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        return new GetMotorcycleAction($motorcycleRepository);
    }
}
