<?php
namespace Rtaranto\Application\Service\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\FrontTireChangeWarningObserver;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\RearTireChangeWarningObserver;

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
        
        $biker->addMotorcycle($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($biker);
        
        $oilChange = new OilChange($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($oilChange);
//        $oilChangeWarningObserver = new OilChangeWarningObserver($motorcycle, $oilChange);
//        $motorcycle->attachMaintenanceWarningObserver($oilChangeWarningObserver);
        
        $rearTireChange = new RearTireChange($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($rearTireChange);
//        $rearTireChangeWarningObserver = new RearTireChangeWarningObserver($motorcycle, $rearTireChange);
//        $motorcycle->attachMaintenanceWarningObserver($rearTireChangeWarningObserver);
        
        $frontTireChange = new FrontTireChange($motorcycle);
        $this->validator->throwValidationFailedIfNotValid($frontTireChange);
//        $frontTireChangeWarningObserver = new FrontTireChangeWarningObserver($motorcycle, $frontTireChange);
//        $motorcycle->attachMaintenanceWarningObserver($frontTireChangeWarningObserver);
        
        $this->validator->throwValidationFailedIfNotValid($motorcycle);
        
        $this->em->persist($motorcycle);
        $this->em->persist($biker);
        $this->em->persist($oilChange);
        $this->em->persist($rearTireChange);
        $this->em->persist($frontTireChange);
        $this->em->flush();
        
        return $motorcycle;
    }
    
}
