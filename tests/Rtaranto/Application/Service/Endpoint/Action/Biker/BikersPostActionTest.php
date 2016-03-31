<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersPostAction;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersPostActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostBiker()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
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
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        
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
