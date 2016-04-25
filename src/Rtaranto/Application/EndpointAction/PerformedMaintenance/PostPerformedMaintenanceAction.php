<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;

abstract class PostPerformedMaintenanceAction implements PostSubresourceActionInterface
{
    protected $inputProcessor;
    
    public function __construct(InputProcessorInterface $inputProcessor)
    {
        $this->inputProcessor = $inputProcessor;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedMaintenanceDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            new PerformedMaintenanceDTO($parentResourceId)
        );

        return $this->createPerformedMaintenance($parentResourceId, $performedMaintenanceDTO);
    }
    
    abstract protected function createPerformedMaintenance(
        $motorcycleId,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
