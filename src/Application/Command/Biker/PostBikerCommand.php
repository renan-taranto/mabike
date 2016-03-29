<?php
namespace Application\Command\Biker;

use Application\Dto\Biker\BikerDTO;
use Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Application\Service\Endpoint\BikersEndpointService;
use Domain\Entity\Biker;

class PostBikerCommand
{
    private $bikersEndpointService;
    
    /**
     * @param BikersPostAction $bikersEndpointService
     */
    public function __construct(BikersEndpointService $bikersEndpointService)
    {
        $this->bikersEndpointService = $bikersEndpointService;
    }
    
    /**
     * @param BikerDTO $postBiker
     * @return Biker
     */
    public function execute(BikerDTO $postBiker)
    {
        return $this->bikersEndpointService->post($postBiker->getName(), $postBiker->getEmail());
    }
}
