<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\Service\PerformedMaintenance\MaintenancePerformerServiceInterface;

class PostPerformedMaintenanceAction implements PostSubresourceActionInterface
{
    private $inputProcessor;
    private $maintenancePerformerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        MaintenancePerformerServiceInterface $maintenancePerformerService
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->maintenancePerformerService = $maintenancePerformerService;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedMaintenanceDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            new PerformedMaintenanceDTO()
        );
        
        return $this->maintenancePerformerService->performMaintenance($parentResourceId, $performedMaintenanceDTO);
    }
}
