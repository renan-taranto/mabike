<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\EndpointAction\GetActionInterface;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class GetWarningsConfigurationAction implements GetActionInterface
{
    private $maintenanceWarningObserverRepository;
    private $oilChangeRepository;
    
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        OilChangeRepositoryInterface $oilChangeRepository
    ) {
        $this->maintenanceWarningObserverRepository = $maintenanceWarningObserverRepository;
        $this->oilChangeRepository = $oilChangeRepository;
    }
    
    public function get($id)
    {
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($id);
        $isActive = $oilChangeWarningObserver->isActive();
        $kmsInAdvance = $oilChangeWarningObserver->getKmsInAdvance();
        
        $oilChange = $this->oilChangeRepository->findOneByMotorcycle($id);
        $kmsPerMaintenance = $oilChange->getKmsPerMaintenance();
        
        return array(
            'is_active' => $isActive,
            'kms_per_oil_change' => $kmsPerMaintenance,
            'kms_in_advance' => $kmsInAdvance
        );
    }
}
