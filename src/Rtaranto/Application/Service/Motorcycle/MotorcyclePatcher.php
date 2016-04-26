<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class MotorcyclePatcher implements MotorcyclePatcherInterface
{
    private $motorcycleRepository;
    private $validator;
    
    public function __construct(
        MotorcycleRepositoryInterface $motorcycleRepository,
        ValidatorInterface $validator
    ) {
        $this->motorcycleRepository = $motorcycleRepository;
        $this->validator = $validator;
    }
    
    public function patchMotorcycle(Motorcycle $motorcycle, MotorcycleDTO $motorcycleDTO)
    {
        $kmsDriven = $motorcycleDTO->getKmsDriven();
        $motorcycle->updateKmsDriven($kmsDriven);
        
        $model = $motorcycleDTO->getModel();
        $motorcycle->setModel($model);
        
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        return $this->motorcycleRepository->update($motorcycle);
    }
}
