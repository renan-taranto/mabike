<?php
namespace Application\Command\Biker;

use Application\Dto\Biker\PostBikerDTO;
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
     * @param PostBikerDTO $postBiker
     * @return Biker
     */
    public function execute(PostBikerDTO $postBiker)
    {
        return $this->bikersEndpointService->post($postBiker->getName(), $postBiker->getEmail());
    }
}
