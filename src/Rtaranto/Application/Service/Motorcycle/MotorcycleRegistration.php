<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class MotorcycleRegistration implements MotorcycleRegistrationInterface
{
    private $motorcycleRepository;
    private $maintenancePerformerRepository;
    private $validator;
    
    public function __construct(
        ValidatorInterface $validator,
        MotorcycleRepositoryInterface $motorcycleRepository,
        MaintenancePerformerRepositoryInterface $maintenancePerformerRepository
    ) {
        $this->validator = $validator;
        $this->motorcycleRepository = $motorcycleRepository;
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
    }
    
    public function registerMotorcycle(Biker $biker, $model, $kmsDriven = 0)
    {
        $motorcycle = new Motorcycle($model, $kmsDriven);
        $motorcycle->setBiker($biker);
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        $this->motorcycleRepository->add($motorcycle);
        
        $maintenancePerformer = new MaintenancePerformer($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($maintenancePerformer);
        $this->maintenancePerformerRepository->add($maintenancePerformer);
        
        return $motorcycle;
    }
    
}
