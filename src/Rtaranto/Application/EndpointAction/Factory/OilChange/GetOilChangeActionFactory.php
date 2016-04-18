<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\GetOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;

class GetOilChangeActionFactory implements GetActionFactoryInterface
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
     * @return GetOilChangeAction
     */
    public function createGetAction()
    {
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        return new GetOilChangeAction($performedOilChangeRepository);
    }

}
