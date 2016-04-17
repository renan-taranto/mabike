<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangePosterInterface;

class PostOilChangeAction implements PostSubresourceActionInterface
{
    private $inputProcessor;
    private $oilChangePoster;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        OilChangePosterInterface $oilChangePoster
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->oilChangePoster = $oilChangePoster;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedMaintenanceDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            new PerformedMaintenanceDTO()
        );
        
        return $this->oilChangePoster->postOilChange($parentResourceId, $performedMaintenanceDTO);
    }
}
