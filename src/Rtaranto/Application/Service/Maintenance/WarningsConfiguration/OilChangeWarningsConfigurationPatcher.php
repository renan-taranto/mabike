<?php
namespace Rtaranto\Application\Service\Maintenance\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\OilChangeWarningsConfigurationDTO;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class OilChangeWarningsConfigurationPatcher implements OilChangeWarningsConfigurationPatcherInterface
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
    
    public function patchOilChangeWarningsConfiguration(
        $motorcycleId,
        OilChangeWarningsConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($motorcycleId);
        $this->patchObserver($oilChangeWarningObserver, $oilChangeWarningsConfigurationDTO);
        
        $oilChange = $this->oilChangeRepository->findOneByMotorcycle($motorcycleId);
        $this->patchOilChange($oilChange, $oilChangeWarningsConfigurationDTO);
        
        
    }
    
    private function patchObserver(
        OilChangeWarningObserver $observer,
        OilChangeWarningsConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        $observer->setKmsInAdvance($oilChangeWarningsConfigurationDTO->getKmsInAdvance());
        if ($oilChangeWarningsConfigurationDTO->getIsActive()) {
            $observer->activate();
            return;
        }
        $observer->deactivate();
    }
    
    private function patchOilChange(
        OilChange $oilChange,
        OilChangeWarningsConfigurationDTO $oilChangeWarningsConfigurationDTO
    ) {
        $oilChange->setKmsPerMaintenance($oilChangeWarningsConfigurationDTO->getKmsPerOilChange());
    }
}
