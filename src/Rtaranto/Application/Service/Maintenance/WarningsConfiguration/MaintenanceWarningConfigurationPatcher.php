<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationsDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\MaintenanceWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

abstract class MaintenanceWarningConfigurationPatcher implements MaintenanceWarningConfigurationPatcherInterface
{
    protected $maintenanceWarningObserverRepository;
    protected $maintenanceRepository;
    protected $validator;
    
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
        MaintenanceWarningConfigurationsDTO $maintenanceWarningsConfigurationDTO
    ) {
        $patchedMaintenanceWarningObserver = $this->patchObserver($motorcycleId, $maintenanceWarningsConfigurationDTO);
        $patchedMaintenance = $this->patchMaintenance($motorcycleId, $maintenanceWarningsConfigurationDTO);
        
        $patchedMaintenanceWarningConfigurationDTO = $this->createPatchedMaintenanceWarningConfigurationDTO(
            $motorcycleId,
            $patchedMaintenance,
            $patchedMaintenanceWarningObserver
        );
        $this->validator->throwValidationFailedIfNotValid($patchedMaintenanceWarningConfigurationDTO);
        
        $this->maintenanceRepository->update($patchedMaintenance);
        $this->maintenanceWarningObserverRepository->update($patchedMaintenanceWarningObserver);
        
        return $patchedMaintenanceWarningConfigurationDTO;
    }
    
    protected function patchObserver(
        $motorcycleId,
        MaintenanceWarningConfigurationsDTO $maintenanceWarningsConfigurationDTO
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
    
    protected function patchMaintenance(
        $motorcycleId,
        MaintenanceWarningConfigurationsDTO $maintenanceWarningsConfigurationDTO
    ) {
        $maintenance = $this->maintenanceRepository->findOneByMotorcycle($motorcycleId);
        $kmsPerMaintenance = $maintenanceWarningsConfigurationDTO->getKmsPerMaintenance();
        $maintenance->setKmsPerMaintenance($kmsPerMaintenance);
        return $maintenance;
    }

    abstract protected function createPatchedMaintenanceWarningConfigurationDTO(
        $motorcycleId,
        Maintenance $patchedMaintenance,
        MaintenanceWarningObserver $patchedMaintenanceWarningObserver
    );
}
