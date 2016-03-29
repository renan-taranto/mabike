<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Dto\Biker\BikerDTO;
use Application\Service\ParametersBinder\ParametersBinder;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
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
        $biker = $this->findBikerOrThrowNotFound($id);
        
        $bikerDTO = $this->parametersBinder->bind($requestBodyParameters, new BikerDTO());
        $this->validator->throwValidationFailedIfNotValid($bikerDTO);
        
        $this->updateBiker($biker, $bikerDTO);
        $this->validator->throwValidationFailedIfNotValid($biker);
        
        return $this->bikerRepository->update($biker);
    }
    
    private function findBikerOrThrowNotFound($id)
    {
        $biker = $this->bikerRepository->get($id);
        if (empty($biker)) {
            throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found.'
                    . ' PUT method must be used only for updating.', $id)
            );
        }
        return $biker;
    }
    
    private function updateBiker(Biker $biker, BikerDTO $bikerDTO)
    {
        $biker->setName($bikerDTO->getName());
        $biker->setEmail($bikerDTO->getEmail());
    }
}
