<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\MaintenanceWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class MaintenanceWarningConfigurationPatcher implements MaintenanceWarningConfigurationPatcherInterface
{
    private $maintenanceWarningObserverRepository;
    private $maintenanceRepository;
    private $validator;
    
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository,
        ValidatorInterface $validator
    ) {
        $this->maintenanceWarningObserverRepository = $maintenanceWarningObserverRepository;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->validator = $validator;
    }
    
    public function patchMaintenanceWarningConfiguration(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $maintenanceWarningsConfigurationDTO
    ) {
        $patchedMaintenanceWarningObserver = $this->patchObserver($motorcycleId, $maintenanceWarningsConfigurationDTO);
        $patchedMaintenance = $this->patchMaintenance($motorcycleId, $maintenanceWarningsConfigurationDTO);
        
        $patchedMaintenanceWarningConfigurationDTO = $this->
            createPatchedMaintenanceWarningConfigurationDTO($patchedMaintenance, $patchedMaintenanceWarningObserver);
        $this->validator->throwValidationFailedIfNotValid($patchedMaintenanceWarningConfigurationDTO);
        
        $this->maintenanceRepository->update($patchedMaintenance);
        $this->maintenanceWarningObserverRepository->update($patchedMaintenanceWarningObserver);
        
        return $patchedMaintenanceWarningConfigurationDTO;
    }
    
    private function patchObserver(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $maintenanceWarningsConfigurationDTO
    ) {
        /* @var $maintenanceWarningObserver MaintenanceWarningObserver */
        $maintenanceWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($motorcycleId);
        
        $kmsInAdvance = $maintenanceWarningsConfigurationDTO->getKmsInAdvance();
        $maintenanceWarningObserver->setKmsInAdvance($kmsInAdvance);
        
        if ($maintenanceWarningsConfigurationDTO->getIsActive()) {
            $maintenanceWarningObserver->activate();
            return $maintenanceWarningObserver;
        }
        
        $maintenanceWarningObserver->deactivate();
        return $maintenanceWarningObserver;
    }
    
    private function patchMaintenance(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $maintenanceWarningsConfigurationDTO
    ) {
        $maintenance = $this->maintenanceRepository->findOneByMotorcycle($motorcycleId);
        $kmsPerMaintenance = $maintenanceWarningsConfigurationDTO->getKmsPerMaintenance();
        $maintenance->setKmsPerMaintenance($kmsPerMaintenance);
        return $maintenance;
    }

    private function createPatchedMaintenanceWarningConfigurationDTO(
        Maintenance $patchedMaintenance,
        MaintenanceWarningObserver $patchedMaintenanceWarningObserver
    ) {
        $isActive = $patchedMaintenanceWarningObserver->isActive();
        $kmsPerMaintenance = $patchedMaintenance->getKmsPerMaintenance();
        $kmsInAdvance = $patchedMaintenanceWarningObserver->getKmsInAdvance();
        return new MaintenanceWarningConfigurationDTO($isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
