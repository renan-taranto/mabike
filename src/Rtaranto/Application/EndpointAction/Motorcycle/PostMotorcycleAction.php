<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PostActionInterface;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistrationServiceInterface;
use Rtaranto\Domain\Entity\Biker;

class PostMotorcycleAction implements PostActionInterface
{
    private $biker;
    private $inputProcessor;
    private $motorcycleRegistration;
    
    public function __construct(
        Biker $biker,
        MotorcycleRegistrationServiceInterface $motorcycleRegistration,
        InputProcessorInterface $inputProcessor
    ) {
        $this->biker = $biker;
        $this->motorcycleRegistration = $motorcycleRegistration;
        $this->inputProcessor = $inputProcessor;
    }
    
    public function post(array $requestBodyParameters)
    {
        /* @var $motorcycleDTO MotorcycleDTO */
        $motorcycleDTO = $this->inputProcessor->processInput($requestBodyParameters, new MotorcycleDTO());
        
        $motorcycle = $this->motorcycleRegistration->registerMotorcycle(
            $this->biker,
            $motorcycleDTO->getModel(),
            $motorcycleDTO->getKmsDriven()
        );
        
        return $motorcycle;
    }
}
