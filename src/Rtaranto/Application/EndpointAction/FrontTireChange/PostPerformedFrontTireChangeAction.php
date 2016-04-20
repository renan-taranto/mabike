<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\TireChange\FrontTireChangerServiceInterface;

class PostPerformedFrontTireChangeAction implements PostSubresourceActionInterface
{
    private $inputProcessor;
    private $frontTireChangerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        FrontTireChangerServiceInterface $frontTireChangerService
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->frontTireChangerService = $frontTireChangerService;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedMaintenanceChangeDTO = $this->inputProcessor
            ->processInput($requestBodyParameters, new PerformedMaintenanceDTO());
        return $this->frontTireChangerService->changeFrontTire($parentResourceId, $performedMaintenanceChangeDTO);
    }

}
