<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\OilChangeWarningsConfigurationDTO;
use Rtaranto\Application\EndpointAction\PatchActionInterface;
use Rtaranto\Application\Service\Maintenance\WarningsConfiguration\OilChangeWarningsConfigurationPatcherInterface;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class PatchWarnigsConfigurationAction implements PatchActionInterface
{
    private $maintenanceWarningObserverRepository;
    private $oilChangeRepository;
    private $oilChangeWarningPatcher;
    
    public function __construct(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        OilChangeRepositoryInterface $oilChangeRepository,
        OilChangeWarningsConfigurationPatcherInterface $oilChangeWarningPatcher,
        InputProcessorInterface $inputProcessor
    ) {
        $this->maintenanceWarningObserverRepository = $maintenanceWarningObserverRepository;
        $this->oilChangeRepository = $oilChangeRepository;
        $this->oilChangeWarningPatcher = $oilChangeWarningPatcher;
    }
    
    public function patch($id, $requestBodyParameters)
    {
        /* @var $oilChangeWarningObserver OilChangeWarningObserver */
        $oilChangeWarningObserver = $this->maintenanceWarningObserverRepository->
            findOneByMotorcycle($id);
        $isActive = $oilChangeWarningObserver->isActive();
        $kmsInAdvance = $oilChangeWarningObserver->getKmsInAdvance();
        
        $oilChange = $this->oilChangeRepository->findOneByMotorcycle($id);
        $kmsPerMaintenance = $oilChange->getKmsPerMaintenance();
        
        $dto = new OilChangeWarningsConfigurationDTO($isActive, $kmsPerMaintenance, $kmsInAdvance);
    }
}
