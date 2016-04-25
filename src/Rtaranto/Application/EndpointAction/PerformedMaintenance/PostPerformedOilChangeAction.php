<?php
namespace Rtaranto\Application\EndpointAction\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\Service\Maintenance\OilChangerServiceInterface;

class PostPerformedOilChangeAction extends PostPerformedMaintenanceAction
{
    private $oilChangerService;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        OilChangerServiceInterface $oilChangerService
    ) {
        parent::__construct($inputProcessor);
        $this->oilChangerService = $oilChangerService;
    }
    
    protected function createPerformedMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        return $this->oilChangerService->changeOil($motorcycleId, $performedMaintenanceDTO);
    }

}
