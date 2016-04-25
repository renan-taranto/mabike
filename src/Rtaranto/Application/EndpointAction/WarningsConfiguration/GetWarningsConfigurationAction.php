<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\EndpointAction\GetActionInterface;
use Rtaranto\Domain\Entity\MaintenanceWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class GetWarningsConfigurationAction implements GetActionInterface
{
    private $maintenanceWarningObserverRepository;
    private $maintenanceRepository;
    
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    ) {
        $this->maintenanceWarningObserverRepository = $maintenanceWarningObserverRepository;
        $this->maintenanceRepository = $maintenanceRepository;
    }
    
    public function get($id)
    {
        /* @var $maintenanceWarningObserver MaintenanceWarningObserver */
        $maintenanceWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($id);
        $isActive = $maintenanceWarningObserver->isActive();
        $kmsInAdvance = $maintenanceWarningObserver->getKmsInAdvance();
        
        $mainetnance = $this->maintenanceRepository->findOneByMotorcycle($id);
        $kmsPerMaintenance = $mainetnance->getKmsPerMaintenance();
        
        return array(
            'is_active' => $isActive,
            'kms_per_oil_change' => $kmsPerMaintenance,
            'kms_in_advance' => $kmsInAdvance
        );
    }
}
