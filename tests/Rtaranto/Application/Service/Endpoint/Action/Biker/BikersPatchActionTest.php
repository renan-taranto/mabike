<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Dto\Biker\BikerDTO;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPatchAction;
use Rtaranto\Application\Service\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersPatchActionTest extends \PHPUnit_Framework_TestCase
{
    public function testPatchReplacesAllProperties()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bindIgnoringMissingFields')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        
        $newName = 'Patched Name';
        $newEmail = 'Patched Email';
        $updatedBiker = new Biker($newName, $newEmail);
        $biker = $this->getMockBuilder(Biker::class)
                ->disableOriginalConstructor()
                ->getMock();
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        $bikerRepository->expects($this->once())
            ->method('update')
            ->will($this->returnValue($updatedBiker));
        
        $bikersPatchAction = new BikersPatchAction($parametersBinder, $validator, $bikerRepository);
        $requestContentParameters = array('name' => $newName, 'email' => $newEmail);
        $expectedBiker = new Biker($newName, $newEmail);
        $returnedBiker = $bikersPatchAction->patch(1, $requestContentParameters);
        $this->assertEquals($expectedBiker, $returnedBiker);
    }
    
    public function testPatchReplacesNamePropertyOnly()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bindIgnoringMissingFields')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        
        $newName = 'Patched Name';
        $updatedBiker = new Biker($newName, 'testbiker@email.com');
        $biker = $this->getMockBuilder(Biker::class)
                ->disableOriginalConstructor()
                ->getMock();
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        $bikerRepository->expects($this->once())
            ->method('update')
            ->will($this->returnValue($updatedBiker));
        
        $bikersPatchAction = new BikersPatchAction($parametersBinder, $validator, $bikerRepository);
        $requestContentParameters = array('name' => $newName);
        $expectedBiker = new Biker($newName, 'testbiker@email.com');
        $returnedBiker = $bikersPatchAction->patch(1, $requestContentParameters);
        $this->assertEquals($expectedBiker, $returnedBiker);
    }
    
    public function testPatchWithInvalidRequestParamsThrowsInvalidValues()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bindIgnoringMissingFields')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $biker = $this->getMockBuilder(Biker::class)
                ->disableOriginalConstructor()
                ->getMock();
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikersPatchAction = new BikersPatchAction($parametersBinder, $validator, $bikerRepository);
        $bikersPatchAction->patch(1, array());
    }
    
    public function testPatchNotFoundBikerThrowsNotFound()
    {
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $validator = $this->getMock(ValidatorInterface::class);
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikersPatchAction = new BikersPatchAction($parametersBinder, $validator, $bikerRepository);
        
        $this->setExpectedException(NotFoundHttpException::class);
        
        $bikersPatchAction->patch(1, array());
    }
    
    public function testPatchBikerWithInvalidBusinessRulesThrowsValidationFailed()
    {
        $bikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bindIgnoringMissingFields')
            ->will($this->returnValue($bikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->at(1))
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $biker = $this->getMockBuilder(Biker::class)
                ->disableOriginalConstructor()
                ->getMock();
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikersPatchAction = new BikersPatchAction($parametersBinder, $validator, $bikerRepository);
        $bikersPatchAction->patch(1, array());
    }
}
