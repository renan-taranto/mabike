<?php
namespace Tests\Application\Service\Endpoint;

use Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Application\Service\Endpoint\Action\Biker\BikersPostActionInterface;
use Application\Service\Endpoint\BikersEndpointService;
use Domain\Entity\Biker;

class BikersEndpointServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testPostBiker()
    {
        $bikerPostAction = $this->getMock(BikersPostActionInterface::class);
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bikerPostAction->expects($this->once())
            ->method('post')
            ->will($this->returnValue($biker));
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        
        $bikersCgetAction = $this->getMock(BikersCgetActionInterface::class);
        
        $bikersEndpointService = new BikersEndpointService($bikerPostAction, $bikersGetAction, $bikersCgetAction);
        
        $returnedBiker = $bikersEndpointService->post('Renan Taranto', 'renantaranto@gmail.com');
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testGetReturnsBiker()
    {
        $bikerPostAction = $this->getMock(BikersPostActionInterface::class);
        
        $bikersCgetAction = $this->getMock(BikersCgetActionInterface::class);
        
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        $bikersGetAction->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        
        $bikersEndpointService = new BikersEndpointService($bikerPostAction, $bikersGetAction, $bikersCgetAction);
        
        $returnedBiker = $bikersEndpointService->get(1);
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testCgetReturnsCollection()
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $bikers = array($biker, $aSecondBiker);
        
        $bikerPostAction = $this->getMock(BikersPostActionInterface::class);
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        
        $bikersCgetAction = $this->getMock(BikersCgetActionInterface::class);
        $bikersCgetAction->expects($this->once())
            ->method('get')
            ->will($this->returnValue($bikers));
        
        $bikersEndpointService = new BikersEndpointService($bikerPostAction, $bikersGetAction, $bikersCgetAction);
        
        $returnedBikers = $bikersEndpointService->getAll();
        
        $this->assertEquals($bikers, $returnedBikers);
    }
}
