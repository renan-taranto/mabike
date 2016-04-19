<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\PerformedOilChangePatcherInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatchPerformedOilChangeAction implements PatchSubresourceActionInterface
{
    private $inputProcessor;
    private $performedOilChangeRepository;
    private $performedOilChangePatcher;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository,
        PerformedOilChangePatcherInterface $performedOilChangePatcher
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->performedOilChangeRepository = $performedOilChangeRepository;
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
    
    /**
     * @param int $motorcycleId
     * @param int $performedOilChangeId
     * @return PerformedOilChange
     * @throws NotFoundHttpException
     */
    private function findOrThrowNotFound($motorcycleId, $performedOilChangeId)
    {
        $performedOilChange = $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($motorcycleId, $performedOilChangeId);
        
        if (empty($performedOilChange)) {
            throw new NotFoundHttpException(
                sprintf('The Oil Change resource of id \'%s\' was not found.', $performedOilChangeId)
            );
        }
        
        return $performedOilChange;
    }
}
