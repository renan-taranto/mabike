<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\Service\Maintenance\RearTireChangerServiceInterface;

class PostPerformedRearTireChangeAction extends PostPerformedMaintenanceAction
{
    private $rearTireChangerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        RearTireChangerServiceInterface $rearTireChangerService
    ) {
        parent::__construct($inputProcessor);
        $this->rearTireChangerService = $rearTireChangerService;
    }
    
    protected function createPerformedMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        return $this->rearTireChangerService->changeRearTire($motorcycleId, $performedMaintenanceDTO);
    }
}
