<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\BasePatchSubResourceAction;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\PerformedOilChangePatcherInterface;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PatchPerformedOilChangeAction extends BasePatchSubResourceAction implements PatchSubresourceActionInterface
{
    private $inputProcessor;
    private $performedOilChangePatcher;
    
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        InputProcessorInterface $inputProcessor,
        PerformedOilChangePatcherInterface $performedOilChangePatcher
    ) {
        parent::__construct($subResourceRepository);
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
}
