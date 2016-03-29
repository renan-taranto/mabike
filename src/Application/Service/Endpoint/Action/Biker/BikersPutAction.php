<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Dto\Biker\BikerDTO;
use Application\Service\ParametersBinder\ParametersBinder;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class BikersPutAction implements BikersPutActionInterface
{
    private $parametersBinder;
    private $validator;
    private $bikerRepository;
    
    public function __construct(
        ParametersBinder $parametersBinder,
        ValidatorInterface $validator,
        BikerRepository $bikerRepository
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
        $this->bikerRepository = $bikerRepository;
    }
    
    public function put($id, array $requestBodyParameters)
    {        
        /* @var $bikerDTO BikerDTO */
        $bikerDTO = $this->parametersBinder->bind($requestBodyParameters, new BikerDTO());
        $this->validator->throwValidationFailedIfNotValid($bikerDTO);
        
        $biker = $this->bikerRepository->get($id);
        if (empty($biker)) {
            return $this->createBiker($bikerDTO, $id);
        }
        return $this->updateBiker($biker, $bikerDTO);
    }
    
    private function createBiker(BikerDTO $bikerDTO, $id)
    {
        $biker = new Biker($bikerDTO->getName(), $bikerDTO->getEmail());
        $this->validator->throwValidationFailedIfNotValid($biker);
        return $this->bikerRepository->addAtId($biker, $id);
    }
    
    private function updateBiker(Biker $biker, BikerDTO $bikerDTO)
    {
        $biker->setName($bikerDTO->getName());
        $biker->setEmail($bikerDTO->getEmail());
        $this->validator->throwValidationFailedIfNotValid($biker);
        return $this->bikerRepository->update($biker);
    }
}
