<?php
namespace Application\Command\Biker;

use Application\Dto\Biker\PutBikerDTO;
use Application\Service\Endpoint\BikersEndpointService;

class PutBikerCommand
{
    private $bikersEndpointService;
    
    public function __construct(BikersEndpointService $bikersEndpointService)
    {
        $this->bikersEndpointService = $bikersEndpointService;
    }
    
    public function execute(PutBikerDTO $putBikerDTO)
    {
        return $this->bikersEndpointService->put(
            $putBikerDTO->getId(),
            $putBikerDTO->getName(),
            $putBikerDTO->getEmail());
    }
}
