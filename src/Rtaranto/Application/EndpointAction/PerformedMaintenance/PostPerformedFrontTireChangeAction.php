<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\Service\Maintenance\FrontTireChangerServiceInterface;

class PostPerformedFrontTireChangeAction extends PostPerformedMaintenanceAction
{
    private $frontTireChangerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        FrontTireChangerServiceInterface $frontTireChangerService
    ) {
        parent::__construct($inputProcessor);
        $this->frontTireChangerService = $frontTireChangerService;
    }
    
    protected function createPerformedMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        return $this->frontTireChangerService->changeFrontTire($motorcycleId, $performedMaintenanceDTO);
    }

}
