<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersGetActionTest extends \PHPUnit_Framework_TestCase
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
        
        $bikersGetAction = new BikersGetAction($bikerRepository);
        $this->assertInstanceOf(Biker::class, $bikersGetAction->get(1));
    }
    
    public function testBikerNotFoundThrowsException()
    {
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));
        
        $this->setExpectedException(NotFoundHttpException::class);
        
        $bikersGetAction = new BikersGetAction($bikerRepository);
        $bikersGetAction->get(1);
    }
}
