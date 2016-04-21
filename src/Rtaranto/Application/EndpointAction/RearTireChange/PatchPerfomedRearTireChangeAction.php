<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubResourceAction;
use Rtaranto\Application\EndpointAction\PatchSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedRearTireChangePatcherInterface;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;

class PatchPerfomedRearTireChangeAction extends PatchSubResourceAction
{
    private $performedRearTireChangeRepository;
    private $inputProcessor;
    private $performedRearTireChangePatcher;
    
    public function __construct(
        PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository,
        InputProcessorInterface $inputProcessor,
        PerformedRearTireChangePatcherInterface $performedRearTireChangePatcher
    ) {
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
        $this->inputProcessor = $inputProcessor;
        $this->performedRearTireChangePatcher = $performedRearTireChangePatcher;
    }
    
    public function patch($parentResourceId, $resourceId, array $requestBodyParameters)
    {
        $performedRearTireChange = $this->findOrThrowNotFound($parentResourceId, $resourceId);
        $performedOilChangeDTO = new PerformedMaintenanceDTO(
            $performedRearTireChange->getKmsDriven(),
            $performedRearTireChange->getDate()
        );
        
        $patchedPerformedOilChangeDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            $performedOilChangeDTO,
            true
        );
        
        $patchedPerformedOilChange = $this->performedRearTireChangePatcher
            ->patchPerformedRearTireChange($performedRearTireChange, $patchedPerformedOilChangeDTO);
        
        return $patchedPerformedOilChange;
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedRearTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }

}
