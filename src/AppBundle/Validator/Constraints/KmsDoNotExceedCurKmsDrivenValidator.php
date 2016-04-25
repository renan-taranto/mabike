<?php
namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class KmsDoNotExceedCurKmsDrivenValidator extends ConstraintValidator
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function validate($value, Constraint $constraint)
    {
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $motorcycle = $motorcycleRepository->get($constraint->getMotorcycleId());
        if ($motorcycle->getKmsDriven() < $value) {
            $this->context->addViolation($constraint->message);
        }
    }
}
