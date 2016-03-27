<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Service\Endpoint\Action\Biker\BikersGetAction;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersGetActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccesfullyGetBiker()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $bikersGetAction = new BikersGetAction($bikerRepository);
        $this->assertInstanceOf(Biker::class, $bikersGetAction->get(1));
    }
    
    public function testBikerNotFoundThrowsException()
    {
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));
        
        $this->setExpectedException(NotFoundHttpException::class);
        
        $bikersGetAction = new BikersGetAction($bikerRepository);
        $bikersGetAction->get(1);
    }
}
