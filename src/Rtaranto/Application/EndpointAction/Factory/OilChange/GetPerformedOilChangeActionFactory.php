<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\GetPerformedOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;

class GetPerformedOilChangeActionFactory implements GetActionFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @return GetPerformedOilChangeAction
     */
    public function createGetAction()
    {
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        return new GetPerformedOilChangeAction($performedOilChangeRepository);
    }

}
