<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchActionInterface;
use Rtaranto\Application\Service\Maintenance\WarningsConfiguration\OilChangeWarningsConfigurationPatcherInterface;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;

class PatchWarnigsConfigurationAction implements PatchActionInterface
{
    private $maintenanceWarningObserverRepository;
    private $maintenanceRepository;
    private $oilChangeWarningPatcher;
    private $inputProcessor;
    
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository,
        OilChangeWarningsConfigurationPatcherInterface $oilChangeWarningPatcher,
        InputProcessorInterface $inputProcessor
    ) {
        $this->maintenanceWarningObserverRepository = $maintenanceWarningObserverRepository;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->oilChangeWarningPatcher = $oilChangeWarningPatcher;
        $this->inputProcessor = $inputProcessor;
    }
    
    public function patch($id, $requestBodyParameters)
    {
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($id);
        $isActive = $oilChangeWarningObserver->isActive();
        $kmsInAdvance = $oilChangeWarningObserver->getKmsInAdvance();
        
        $oilChange = $this->maintenanceRepository->findOneByMotorcycle($id);
        $kmsPerMaintenance = $oilChange->getKmsPerMaintenance();
        
        $oilChangeWarningsConfigurationDTO = new MaintenanceWarningConfigurationDTO($isActive, $kmsPerMaintenance, $kmsInAdvance);
        $patchedOilChangeWarningsConfigurationDTO = $this->inputProcessor->processInputIgnoringMissingFields(
            $requestBodyParameters,
            $oilChangeWarningsConfigurationDTO
        );
        
        $patchedPerformedMaintenance = $this->oilChangeWarningPatcher
            ->patchOilChangeWarningsConfiguration($id, $patchedOilChangeWarningsConfigurationDTO);
        
        return $patchedPerformedMaintenance;
    }
}
