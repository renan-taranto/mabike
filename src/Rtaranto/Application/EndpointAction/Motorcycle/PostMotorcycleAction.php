<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\PostActionInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class PostMotorcycleAction implements PostActionInterface
{
    private $biker;
    private $motorcycleRepository;
    private $parametersBinder;
    private $validator;
    
    public function __construct(
        Biker $biker,
        MotorcycleRepositoryInterface $motorcycleRepository,
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator
    ) {
        $this->biker = $biker;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
    }
    
    public function post(array $requestBodyParameters)
    {
        $motorcycleDTO = $this->parametersBinder->bind($requestBodyParameters, new MotorcycleDTO());
        $this->validator->throwValidationFailedIfNotValid($motorcycleDTO);
        
        $motorcycle = $this->createMotorcycle($motorcycleDTO);
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        
        return $this->motorcycleRepository->add($motorcycle);
    }
    
    private function createMotorcycle(MotorcycleDTO $motorcycleDTO)
    {
        $motorcycle = new Motorcycle($motorcycleDTO->getModel(), $motorcycleDTO->getKmsDriven());
        $motorcycle->setBiker($this->biker);
        return $motorcycle;
    }
}
