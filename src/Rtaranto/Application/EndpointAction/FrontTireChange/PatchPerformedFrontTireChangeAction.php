<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\BasePatchSubResourceAction;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedFrontTireChangePatcherInterface;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PatchPerformedFrontTireChangeAction extends BasePatchSubResourceAction implements PatchSubresourceActionInterface
{
    private $inputProcessor;
    private $performedFrontTireChangePatcher;
    
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        InputProcessorInterface $inputProcessor,
        PerformedFrontTireChangePatcherInterface $performedRearTireChangePatcher
    ) {
        parent::__construct($subResourceRepository);
        $this->inputProcessor = $inputProcessor;
        $this->performedFrontTireChangePatcher = $performedRearTireChangePatcher;
    }
    
    public function patch($parentResourceId, $resourceId, array $requestBodyParameters)
    {
        $performedFrontTireChange = $this->findOrThrowNotFound($parentResourceId, $resourceId);
        $performedMaintenanceDTO = new PerformedMaintenanceDTO(
            $performedFrontTireChange->getKmsDriven(),
            $performedFrontTireChange->getDate()
        );
        
        $patchedPerformedFrontTireChangeDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            $performedMaintenanceDTO,
            true
        );
        
        $patchedPerformedOilChange = $this->performedFrontTireChangePatcher
            ->patchPerformedFrontTireChange($performedFrontTireChange, $patchedPerformedFrontTireChangeDTO);
        
        return $patchedPerformedOilChange;
    }
}
