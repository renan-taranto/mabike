<?php
namespace Rtaranto\Application\Command\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Rtaranto\Application\Service\Endpoint\BikersEndpointService;
use Rtaranto\Domain\Entity\Biker;

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
