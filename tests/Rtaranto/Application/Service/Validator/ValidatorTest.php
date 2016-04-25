<?php
namespace Tests\Rtaranto\Application\Service;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Validator\Validator;
use stdClass;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateThrowsValidationFailed()
    {
        $nameProperty = 'name';
        $nameErrorMessage = 'This value should not be blank.';
        
        $constraintViolation = $this->getMock(ConstraintViolationInterface::class);
        $constraintViolation->expects($this->any())
            ->method('getPropertyPath')
            ->will($this->returnValue($nameProperty));
        $constraintViolation->expects($this->any())
            ->method('getMessage')
            ->will($this->returnValue($nameErrorMessage));
                
        $constraintViolationList = $this->getMock(ConstraintViolationListInterface::class);
        $constraintViolationList->expects($this->any())
            ->method('count')
            ->will($this->returnValue(1));
        $constraintViolationList->expects($this->any())
            ->method('get')
            ->will($this->returnValue($constraintViolation));
        
        
        $symfonyValidatorComponent = $this->getMock(ValidatorInterface::class);
        $symfonyValidatorComponent->expects($this->any())
            ->method('validate')
            ->will($this->returnValue($constraintViolationList));
        
        $validator = new Validator($symfonyValidatorComponent);
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $validator->throwValidationFailedIfNotValid(new stdClass());
    }
}
