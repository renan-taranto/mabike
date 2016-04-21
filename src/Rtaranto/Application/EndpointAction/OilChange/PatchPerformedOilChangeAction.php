<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubResourceAction;
use Rtaranto\Application\Service\Maintenance\OilChange\PerformedOilChangePatcherInterface;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class PatchPerformedOilChangeAction extends PatchSubResourceAction
{
    private $performedOilChangeRepository;
    private $inputProcessor;
    private $performedOilChangePatcher;
    
    public function __construct(
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository,
        InputProcessorInterface $inputProcessor,
        PerformedOilChangePatcherInterface $performedOilChangePatcher
    ) {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
        $this->inputProcessor = $inputProcessor;
        $this->performedOilChangePatcher = $performedOilChangePatcher;
    }

    public function patch($parentResourceId, $resourceId, array $requestBodyParameters)
    {
        $performedOilChange = $this->findOrThrowNotFound($parentResourceId, $resourceId);
        $performedOilChangeDTO = new PerformedMaintenanceDTO(
            $performedOilChange->getKmsDriven(),
            $performedOilChange->getDate()
        );
        
        $patchedPerformedOilChangeDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            $performedOilChangeDTO,
            true
        );
        
        $patchedPerformedOilChange = $this->performedOilChangePatcher
            ->patchPerformedOilChange($performedOilChange, $patchedPerformedOilChangeDTO);
        
        return $patchedPerformedOilChange;
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }

}
