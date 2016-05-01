<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationsDTOFactoryInterface;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchActionInterface;
use Rtaranto\Application\Service\Maintenance\WarningsConfiguration\MaintenanceWarningConfigurationPatcherInterface;

class PatchWarnigsConfigurationAction implements PatchActionInterface
{
    private $maintenanceWarningConfigurationsDTOFactory;
    private $maintenanceWarningConfigurationPatcher;
    private $inputProcessor;
    
    public function __construct(
        MaintenanceWarningConfigurationsDTOFactoryInterface $maintenanceWarningConfigurationsDTOFactory,
        MaintenanceWarningConfigurationPatcherInterface $maintenanceWarningConfigurationPatcher,
        InputProcessorInterface $inputProcessor
    ) {
        $this->maintenanceWarningConfigurationsDTOFactory = $maintenanceWarningConfigurationsDTOFactory;
        $this->maintenanceWarningConfigurationPatcher = $maintenanceWarningConfigurationPatcher;
        $this->inputProcessor = $inputProcessor;
    }
    
    public function patch($id, $requestBodyParameters)
    {
        $maintenanceWarningsConfigurationDTO = $this->maintenanceWarningConfigurationsDTOFactory->
            createMaintenanceWarningConfigurationDTO($id);
        $patchedMaintenanceWarningsConfigurationDTO = $this->inputProcessor->processInputIgnoringMissingFields(
            $requestBodyParameters,
            $maintenanceWarningsConfigurationDTO
        );
        
        $patchedPerformedMaintenance = $this->maintenanceWarningConfigurationPatcher
            ->patchMaintenanceWarningConfiguration($id, $patchedMaintenanceWarningsConfigurationDTO);
        
        return $patchedPerformedMaintenance;
    }
}
