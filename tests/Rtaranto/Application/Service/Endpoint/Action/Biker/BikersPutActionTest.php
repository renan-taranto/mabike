<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPutAction;
use Rtaranto\Application\Service\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersPutActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPutBiker()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        
        $biker = new Biker('anyname', 'anyemail');
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        $name = 'Update Name Test';
        $email = 'Update Email Test';
        $updatedBiker = new Biker($name, $email);
        $bikerRepository->expects($this->once())
            ->method('update')
            ->will($this->returnValue($updatedBiker));
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $requestBodyParameters = array('name' => $name, 'email' => $email);
        
        $returnedBiker = $bikersPutAction->put(1, $requestBodyParameters);
        $this->assertEquals($updatedBiker, $returnedBiker);
    }
    
    public function testInvalidRequestParamsThrowsValidationFailed()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->any())
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $this->setExpectedException(ValidationFailedException::class);
        $returnedBiker = $bikersPutAction->put(1, array('name' => 'smallName', 'email' => 'invalidEmail'));
    }
    
    public function testBusinessRulesViolationsThrowsValidationFailed()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->at(1))
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $biker = new Biker('anyname', 'anyemail');
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $this->setExpectedException(ValidationFailedException::class);
        $data = array('name' => 'Any Name Already in use', 'email' => 'Any Email already in use');
        $returnedBiker = $bikersPutAction->put(1, $data);
    }
    
    public function testPutCreatesNewBiker()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        
        $biker = $this->getMockBuilder(Biker::class)
                ->disableOriginalConstructor()
                ->getMock();
        $bikerRepository->expects($this->once())
            ->method('addAtId')
            ->will($this->returnValue($biker));
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $this->assertEquals($biker, $bikersPutAction->put(9999, array()));
    }
}
