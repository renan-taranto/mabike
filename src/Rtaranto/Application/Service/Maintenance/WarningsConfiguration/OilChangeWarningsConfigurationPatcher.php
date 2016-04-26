<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class OilChangeWarningsConfigurationPatcher implements OilChangeWarningsConfigurationPatcherInterface
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
    
    public function patchOilChangeWarningsConfiguration(
        $motorcycleId,
        MaintenanceWarningConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($motorcycleId);
        $patchedWarningObserver = $this->patchObserver($oilChangeWarningObserver, $oilChangeWarningsConfigurationDTO);
        $oilChange = $this->maintenanceRepository->findOneByMotorcycle($motorcycleId);
        $patchedOilChange = $this->patchOilChange($oilChange, $oilChangeWarningsConfigurationDTO);
        
        $isActive = $patchedWarningObserver->isActive();
        $kmsPerOilChange = $patchedOilChange->getKmsPerMaintenance();
        $kmsInAdvance = $patchedWarningObserver->getKmsInAdvance();
        $dto = new MaintenanceWarningConfigurationDTO($isActive, $kmsPerOilChange, $kmsInAdvance);
        $this->validator->throwValidationFailedIfNotValid($dto);
        
        $this->maintenanceRepository->update($patchedOilChange);
        $this->maintenanceWarningObserverRepository->update($patchedWarningObserver);
        
        return $dto;
    }
    
    private function patchObserver(
        OilChangeWarningObserver $observer,
        MaintenanceWarningConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        $observer->setKmsInAdvance($oilChangeWarningsConfigurationDTO->getKmsInAdvance());
        if ($oilChangeWarningsConfigurationDTO->getIsActive()) {
            $observer->activate();
            return $observer;
        }
        
        $observer->deactivate();
        return $observer;
    }
    
    private function patchOilChange(
        OilChange $oilChange,
        MaintenanceWarningConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        $oilChange->setKmsPerMaintenance($oilChangeWarningsConfigurationDTO->getKmsPerMaintenance());
        return $oilChange;
    }
}
