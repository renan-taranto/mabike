<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\GetOilChangeAction;
use Rtaranto\Domain\Entity\MaintenancePerformer;

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
        $maintenancePerformerRepository = $this->em->getRepository(MaintenancePerformer::class);
        return new GetOilChangeAction($maintenancePerformerRepository);
    }

}
