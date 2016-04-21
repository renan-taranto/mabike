<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubResourceAction;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedFrontTireChangePatcherInterface;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class PatchPerformedFrontTireChangeAction extends PatchSubResourceAction
{
    private $inputProcessor;
    private $performedFrontTireChangePatcher;
    private $performedFrontTireChangeRepository;
    
    public function __construct(
        PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository,
        InputProcessorInterface $inputProcessor,
        PerformedFrontTireChangePatcherInterface $performedRearTireChangePatcher
    ) {
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
        $this->inputProcessor = $inputProcessor;
        $this->performedFrontTireChangePatcher = $performedRearTireChangePatcher;
    }
    
    public function patch($parentResourceId, $resourceId, array $requestBodyParameters)
    {
        $performedFrontTireChange = $this->findOrThrowNotFound($parentResourceId, $resourceId);
        
        $requestAsDTO = $this->getRequestAsValidDTO($performedFrontTireChange, $requestBodyParameters);
        $patchedPerformedOilChange = $this->performedFrontTireChangePatcher
            ->patchPerformedFrontTireChange($performedFrontTireChange, $requestAsDTO);
        
        return $patchedPerformedOilChange;
    }
    
    private function getRequestAsValidDTO(
        PerformedFrontTireChange $performedFrontTireChange,
        array $requestBodyParameters
    ) {
        $performedMaintenanceDTO = $this->getPerformedFrontTireChangeAsDTO($performedFrontTireChange);
        
        return $this->inputProcessor->processInput(
            $requestBodyParameters,
            $performedMaintenanceDTO,
            true
        );
    }
    
    private function getPerformedFrontTireChangeAsDTO(PerformedFrontTireChange $performedFrontTireChange)
    {
        return new PerformedMaintenanceDTO(
            $performedFrontTireChange->getKmsDriven(),
            $performedFrontTireChange->getDate()
        );
    }
    
    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedFrontTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }

}
