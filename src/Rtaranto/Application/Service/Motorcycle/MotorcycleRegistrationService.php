<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\OilChange;

class MotorcycleRegistrationService implements MotorcycleRegistrationServiceInterface
{
    private $em;
    private $validator;
    
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->validator = $validator;
        $this->em = $em;
    }
    
    public function registerMotorcycle(Biker $biker, $model, $kmsDriven = 0)
    {
        $motorcycle = new Motorcycle($model, $kmsDriven);
        $motorcycle->setBiker($biker);
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        
        $biker->addMotorcycle($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($biker);
        
        $oilChange = new OilChange($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($oilChange);
        
        $this->em->persist($motorcycle);
        $this->em->persist($biker);
        $this->em->persist($oilChange);
        $this->em->flush();

        return $motorcycle;
    }
    
}
