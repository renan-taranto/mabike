<?php
namespace Tests\Rtaranto\Application\Service;

use Rtaranto\Application\Service\Validator\Validator;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidateReturnsErrors()
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
        
        $errors = $validator->getErrors(new stdClass());
        
        $expectedArray = array(
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Validation Failed',
            'errors' => array('name' => array('This value should not be blank.')));
        
        $this->assertEquals($expectedArray, $errors);
    }
    
    public function testIsValidReturnsFalse()
    {
        $symfonyValidatorComponent = $this->getMock(ValidatorInterface::class);
        $symfonyValidatorComponent->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(array(1)));
        $validator = new Validator($symfonyValidatorComponent);
        $this->assertFalse($validator->isValid(new stdClass()));
    }
    
    public function testIsValidReturnsTrue()
    {
        $symfonyValidatorComponent = $this->getMock(ValidatorInterface::class);
        $symfonyValidatorComponent->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(null));
        $validator = new Validator($symfonyValidatorComponent);
        $this->assertTrue($validator->isValid(new stdClass()));
    }
    
    public function testGetErrorsReturnsNull()
    {
        $symfonyValidatorComponent = $this->getMock(ValidatorInterface::class);
        $symfonyValidatorComponent->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(null));
        $validator = new Validator($symfonyValidatorComponent);
        $this->assertNull($validator->getErrors(new stdClass()));
    }
}