<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Service\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersPostAction implements BikersPostActionInterface
{
    private $parametersBinder;
    private $validator;
    private $bikerRepository;
    
    public function __construct(
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator,
        BikerRepositoryInterface $bikerRepository
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
        $this->bikerRepository = $bikerRepository;
    }

    public function post(array $requestBodyParameters)
    {
        /* @var $bikerDTO BikerDTO */
        $bikerDTO = $this->parametersBinder->bind($requestBodyParameters, new BikerDTO());
        $this->validator->throwValidationFailedIfNotValid($bikerDTO);
        
        $biker = $this->createBiker($bikerDTO);
        return $this->bikerRepository->add($biker);
    }

    private function createBiker(BikerDTO $bikerDTO)
    {
        $biker = new Biker($bikerDTO->getName(), $bikerDTO->getEmail());
        $this->validator->throwValidationFailedIfNotValid($biker);
        return $biker;
    }
}
