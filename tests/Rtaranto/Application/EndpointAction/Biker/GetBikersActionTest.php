<?php
namespace Tests\Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\Biker\GetBikerAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetBikersActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccesfullyGetBiker()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $bikersGetAction = new GetBikerAction($bikerRepository);
        $this->assertInstanceOf(Biker::class, $bikersGetAction->get(1));
    }
    
    public function testBikerNotFoundThrowsException()
    {
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));
        
        $this->setExpectedException(NotFoundHttpException::class);
        
        $bikersGetAction = new GetBikerAction($bikerRepository);
        $bikersGetAction->get(1);
    }
}
