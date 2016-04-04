<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Command\Biker\PatchBikerCommand;
use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Service\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersPatchAction implements BikersPatchActionInterface
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
    
    public function patch($id, $requestBodyParameters)
    {
        $biker = $this->findBikerOrThrowNotFound($id);

        $bikerDTO = new BikerDTO($biker->getName(), $biker->getEmail());
        /* @var $bikerDTO BikerDTO */
        $bikerDTO = $this->parametersBinder->bindIgnoringMissingFields($requestBodyParameters, $bikerDTO);
        $this->validator->throwValidationFailedIfNotValid($bikerDTO);
        
        $patchBikerCommand = new PatchBikerCommand($biker);
        $patchBikerCommand->execute($bikerDTO);
        $this->validator->throwValidationFailedIfNotValid($biker);
        
        return $this->bikerRepository->update($biker);
    }
    
    /**
     * @param integer $id
     * @return Biker
     * @throws NotFoundHttpException
     */
    private function findBikerOrThrowNotFound($id)
    {
       $biker = $this->bikerRepository->get($id);
        if (empty($biker)) {
           throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found.', $id));
        }
        return $biker;
    }
}
