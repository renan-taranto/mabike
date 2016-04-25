<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceWarninObserverRepository;

class GetWarningsConfigurationActionFactory implements GetActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function createGetAction()
    {
        $oilChangeWarningObserverRepository = new DoctrineMaintenanceWarninObserverRepository(
            $this->em,
            OilChangeWarningObserver::class
        );
        $oilChangeRepository = new DoctrineMaintenanceRepository($this->em, OilChange::class);
        return new GetWarningsConfigurationAction($oilChangeWarningObserverRepository, $oilChangeRepository);
    }
}
