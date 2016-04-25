<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubResourceAction;
use Rtaranto\Application\Service\PerformedMaintenance\PerformedMaintenancePatcherInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class PatchPerformedMaintenanceAction extends PatchSubResourceAction
{
    private $performedMaintenanceRepository;
    private $inputProcessor;
    private $performedMaintenancePatcher;
    
    public function __construct(
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository,
        InputProcessorInterface $inputProcessor,
        PerformedMaintenancePatcherInterface $performedMaintenancePatcher
    ) {
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
        $this->inputProcessor = $inputProcessor;
        $this->performedMaintenancePatcher = $performedMaintenancePatcher;
    }
    
    public function patch($parentResourceId, $resourceId, array $requestBodyParameters)
    {
        $performedMaintenance = $this->findOrThrowNotFound($parentResourceId, $resourceId);
        $performedMaintenanceDTO = new PerformedMaintenanceDTO(
            $parentResourceId,
            $performedMaintenance->getKmsDriven(),
            $performedMaintenance->getDate()
        );
        
        $patchedPerformedPerformedDTO = $this->inputProcessor->processInputIgnoringMissingFields(
            $requestBodyParameters,
            $performedMaintenanceDTO
        );
        
        $patchedPerformedMaintenance = $this->performedMaintenancePatcher
            ->patchPerformedMaintenance($performedMaintenance, $patchedPerformedPerformedDTO);
        
        return $patchedPerformedMaintenance;
    }

    protected function findSubResourceByParentResource($parentResourceId, $subResourceId)
    {
        return $this->performedMaintenanceRepository->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }

}
