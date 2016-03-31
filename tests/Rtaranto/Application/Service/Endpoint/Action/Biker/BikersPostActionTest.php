<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Rtaranto\Application\Service\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersPostActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostBiker()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('add')
            ->will($this->returnValue($biker));
        
        $validator = $this->getMock(ValidatorInterface::class);
        
        $bikerPostAction = new BikersPostAction($parametersBinder, $validator, $bikerRepository);
        $requestBodyParams = array('Test Biker', 'testbike@email.com');
        $returnedBiker = $bikerPostAction->post($requestBodyParams);
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testInvalidRequestParamsThrowsValidationFailed()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->any())
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $bikerPostAction = new BikersPostAction($parametersBinder, $validator, $bikerRepository);
        
        $this->setExpectedException(ValidationFailedException::class);
        $requestBodyParams = array();
        $bikerPostAction->post($requestBodyParams);
    }
    
    public function testBusinessRulesViolationsThrowsValidationFailed()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($bikerDTO));
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->at(1))
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $bikerPostAction = new BikersPostAction($parametersBinder, $validator, $bikerRepository);
        
        $this->setExpectedException(ValidationFailedException::class);
        $requestBodyParams = array('name' => 'smallName', 'email' => 'invalidEmail');
        $bikerPostAction->post($requestBodyParams);
    }
}
