<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Exception\ValidationFailedException;
use Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class BikersPostActionTest extends \PHPUnit_Framework_TestCase
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
            ->method('isValid')
            ->will($this->returnValue(true));
        
        $bikerPostAction = new BikersPostAction($bikerRepository, $validator);
        
        $returnedBiker = $bikerPostAction->post('Test Biker', 'testbike@email.com');
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testPostInvalidBikerthrowsException()
    {
        $bikerRepository = $this->getMock(BikerRepository::class);
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $validator->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue(array()));
        
        $bikerPostAction = new BikersPostAction($bikerRepository, $validator);
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostAction->post('anyInvalidName', 'anyInvalidEmail');
    }
}