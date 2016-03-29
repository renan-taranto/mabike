<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Dto\Biker\BikerDTO;
use Application\Exception\ValidationFailedException;
use Application\Service\ParametersBinder\ParametersBinder;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Repository\BikerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $putBikerDTO = $this->parametersBinder->bind($requestBodyParameters, new BikerDTO());
        $this->validateRequestAsDTO($putBikerDTO);
        $biker = $this->bikerRepository->get($id);
        if (empty($biker)) {
            throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found. PUT method must be used only for updating.', $id)
            );
        }
        $this->updateBiker($biker, $putBikerDTO);
        $this->validateUpdatedBiker($biker);
        
        return $this->bikerRepository->update($biker);
    }
    
    private function validateRequestAsDTO($putBikerDTO)
    {   
        if (!$this->validator->isValid($putBikerDTO)) {
            $errors = $this->validator->getErrors($putBikerDTO);
            throw new ValidationFailedException($errors);
        }
    }
    
    private function updateBiker($biker, $putBikerDTO)
    {
        $biker->setName($putBikerDTO->getName());
        $biker->setEmail($putBikerDTO->getEmail());
    }
    
    private function validateUpdatedBiker($biker)
    {
        if (!$this->validator->isValid($biker)) {
            $errors = $this->validator->getErrors($biker);
            throw new ValidationFailedException($errors);
        }
    }
}
