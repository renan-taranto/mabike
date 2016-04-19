<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\TireChange\RearTireChangerServiceInterface;

class PostPerformedRearTireChangeAction implements PostSubresourceActionInterface
{
    private $inputProcessor;
    private $rearTireChangerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        RearTireChangerServiceInterface $rearTireChangerService
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->rearTireChangerService = $rearTireChangerService;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedRearTireChangeDTO = $this->inputProcessor
            ->processInput($requestBodyParameters, new PerformedMaintenanceDTO());
        return $this->rearTireChangerService->changeRearTire($parentResourceId, $performedRearTireChangeDTO);
    }
}
