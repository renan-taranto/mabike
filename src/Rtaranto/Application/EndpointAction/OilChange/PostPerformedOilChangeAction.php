<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangerServiceInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;

class PostPerformedOilChangeAction implements PostSubresourceActionInterface
{
    private $inputProcessor;
    private $oilChanger;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        OilChangerServiceInterface $oilChangePoster
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->oilChanger = $oilChangePoster;
    }
    
    /**
     * @param int $parentResourceId Motorcycle id
     * @param array $requestBodyParameters Request body contents
     * @return PerformedOilChange
     */
    public function post($parentResourceId, array $requestBodyParameters)
    {
        $performedMaintenanceDTO = $this->inputProcessor->processInput(
            $requestBodyParameters,
            new PerformedMaintenanceDTO()
        );
        
        return $this->oilChanger->changeOil($parentResourceId, $performedMaintenanceDTO);
    }
}