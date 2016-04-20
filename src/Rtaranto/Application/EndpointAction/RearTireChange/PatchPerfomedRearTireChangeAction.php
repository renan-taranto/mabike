<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\BasePatchSubResourceAction;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedRearTireChangePatcherInterface;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PatchPerfomedRearTireChangeAction extends BasePatchSubResourceAction implements PatchSubresourceActionInterface
{
    private $inputProcessor;
    private $performedRearTireChangePatcher;
    
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        InputProcessorInterface $inputProcessor,
        PerformedRearTireChangePatcherInterface $performedRearTireChangePatcher
    ) {
        parent::__construct($subResourceRepository);
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
}
