<?php
namespace Rtaranto\Application\Dto\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationsDTOFactoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

abstract class MaintenanceWarningConfigurationsDTOFactory implements MaintenanceWarningConfigurationsDTOFactoryInterface
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
    
    public function createMaintenanceWarningConfigurationDTO($motorcycle)
    {
        $maintenanceWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($motorcycle);
        $isActive = $maintenanceWarningObserver->isActive();
        $kmsInAdvance = $maintenanceWarningObserver->getKmsInAdvance();
        
        $maintenance = $this->maintenanceRepository->findOneByMotorcycle($motorcycle);
        $kmsPerMaintenance = $maintenance->getKmsPerMaintenance();
        
        $id = $motorcycle;
        if ($motorcycle instanceof \Rtaranto\Domain\Entity\Motorcycle) {
            $id = $motorcycle->getId();
        }
        
        return $this->createMaintenanceConfigurationDTO($id, $isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
    
    abstract function createMaintenanceConfigurationDTO($motorcycleId, $isActive, $kmsPerMaintenance, $kmsInAdvance);

}
