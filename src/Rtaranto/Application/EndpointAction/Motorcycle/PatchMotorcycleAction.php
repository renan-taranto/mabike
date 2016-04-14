<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Command\Motorcycle\PatchMotorcycleCommand;
use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\PatchActionInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatchMotorcycleAction implements PatchActionInterface
{
    private $motorcycleRepository;
    private $biker;
    private $parametersBinder;
    private $validator;
    
    /**
     * @param MotorcycleRepositoryInterface $motorcycleRepository
     * @param Biker $biker
     * @param ParametersBinderInterface $parametersBinder
     * @param ValidatorInterface $validator
     */
    public function __construct(
        MotorcycleRepositoryInterface $motorcycleRepository,
        Biker $biker,
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator    
    ) {
        $this->motorcycleRepository = $motorcycleRepository;
        $this->biker = $biker;
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
    }
    
    /**
     * @param int $id
     * @param array $requestBodyParameters
     * @return Motorcycle
     */
    public function patch($id, $requestBodyParameters)
    {
        $motorcycle = $this->findOrThrowNotFound($id);
        
        $motorcycleDTO = new MotorcycleDTO($motorcycle->getModel(), $motorcycle->getKmsDriven());
        $this->parametersBinder->bindIgnoringMissingFields($requestBodyParameters, $motorcycleDTO);
        $this->validator->throwValidationFailedIfNotValid($motorcycleDTO);
        
        $patchMotorcycleCommand = new PatchMotorcycleCommand($motorcycle);
        $patchMotorcycleCommand->execute($motorcycleDTO);
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        
        return $this->motorcycleRepository->update($motorcycle);
    }
    
    /**
     * @param int $id
     * @return Motorcycle
     * @throws NotFoundHttpException
     */
    private function findOrThrowNotFound($id)
    {
        $motorcycle = $this->motorcycleRepository->findOneByBikerAndId($this->biker, $id);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $id)
            );
        }
        
        return $motorcycle;
    }
}
