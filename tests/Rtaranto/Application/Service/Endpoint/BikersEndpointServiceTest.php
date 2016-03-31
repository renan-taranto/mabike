<?php
namespace Tests\Rtaranto\Application\Service\Endpoint;

use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetActionInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Application\Service\Endpoint\BikersEndpointService;
use Rtaranto\Domain\Entity\Biker;

class BikersEndpointServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReturnsBiker()
    {
        $bikersCgetAction = $this->getMock(BikersCgetActionInterface::class);
        
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        $bikersGetAction->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        
        $bikersEndpointService = new BikersEndpointService($bikersGetAction, $bikersCgetAction);
        
        $returnedBiker = $bikersEndpointService->get(1);
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testCgetReturnsCollection()
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $bikers = array($biker, $aSecondBiker);
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        
        $bikersCgetAction = $this->getMock(BikersCgetActionInterface::class);
        $bikersCgetAction->expects($this->once())
            ->method('get')
            ->will($this->returnValue($bikers));
        
        $bikersEndpointService = new BikersEndpointService($bikersGetAction, $bikersCgetAction);
        
        $returnedBikers = $bikersEndpointService->getAll();
        
        $this->assertEquals($bikers, $returnedBikers);
    }
}
