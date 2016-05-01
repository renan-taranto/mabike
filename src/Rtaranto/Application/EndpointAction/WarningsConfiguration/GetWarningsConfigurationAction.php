<?php
namespace Rtaranto\Application\EndpointAction\WarningsConfiguration;

use Rtaranto\Application\Dto\WarningsConfiguration\MaintenanceWarningConfigurationsDTOFactoryInterface;
use Rtaranto\Application\EndpointAction\GetActionInterface;

class GetWarningsConfigurationAction implements GetActionInterface
{
    private $maintenanceWarningConfigurationDTOFactory;
    
    public function __construct(
        MaintenanceWarningConfigurationsDTOFactoryInterface $maintenanceWarningConfigurationDTOFactory
    ) {
        $this->maintenanceWarningConfigurationDTOFactory = $maintenanceWarningConfigurationDTOFactory;
    }
    
    public function get($id)
    {
        return $this->maintenanceWarningConfigurationDTOFactory->createMaintenanceWarningConfigurationDTO($id);
    }
}
