<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;
use Exception;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BikerPostActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostBiker()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('add')
            ->will($this->returnValue($biker));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(array()));
        
        $bikerPostAction = new BikersPostAction($bikerRepository, $validator);
        $returnedBiker = $bikerPostAction->post('Test Biker', 'testbike@email.com');
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testPostInvalidBikerthrowsException()
    {
        $bikerRepository = $this->getMock(BikerRepository::class);
        
        $constraintViolationInterface = $this->getMock(ConstraintViolationInterface::class);
        $constraintViolationInterface->expects($this->once())
            ->method('getMessage');
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(array($constraintViolationInterface)));
        
        $bikerPostAction = new BikersPostAction($bikerRepository, $validator);
        
        $this->setExpectedException(Exception::class);
        
        $bikerPostAction->post('anyInvalidName', 'anyInvalidEmail');
    }
}
