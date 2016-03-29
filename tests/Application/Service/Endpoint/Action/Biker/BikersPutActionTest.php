<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Dto\Biker\BikerDTO;
use Application\Dto\Biker\PutBikerDTO;
use Application\Exception\ValidationFailedException;
use Application\Service\Endpoint\Action\Biker\BikersPutAction;
use Application\Service\ParametersBinder\ParametersBinder;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class BikersPutActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPutBiker()
    {
        $putBikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinder::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($putBikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        
        $biker = new Biker('anyname', 'anyemail');
        $bikerRepository = $this->getMock(BikerRepository::class);
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
    
    public function testInvalidRequestParamsThrowsException()
    {
        $putBikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinder::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($putBikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));
        $validator->expects($this->any())
            ->method('getErrors')
            ->will($this->returnValue(array()));
        
        $bikerRepository = $this->getMock(BikerRepository::class);
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $this->setExpectedException(ValidationFailedException::class);
        $returnedBiker = $bikersPutAction->put(1, array('name' => 'smallName', 'email' => 'invalidEmail'));
    }
    
    public function testBusinessRulesViolationsThrowsException()
    {
        $putBikerDTO = $this->getMock(BikerDTO::class);
        $parametersBinder = $this->getMock(ParametersBinder::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue($putBikerDTO));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->at(0))
            ->method('isValid')
            ->will($this->returnValue(true));
        $validator->expects($this->at(1))
            ->method('isValid')
            ->will($this->returnValue(false));
        $validator->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue(array()));
        
        $bikerRepository = $this->getMock(BikerRepository::class);
        $biker = new Biker('anyname', 'anyemail');
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $bikersPutAction = new BikersPutAction($parametersBinder, $validator, $bikerRepository);
        $this->setExpectedException(ValidationFailedException::class);
        $data = array('name' => 'Any Name Already in use', 'email' => 'Any Email already in use');
        $returnedBiker = $bikersPutAction->put(1, $data);
    }
}
