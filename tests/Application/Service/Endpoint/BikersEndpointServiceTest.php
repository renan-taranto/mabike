<?php
namespace Tests\Application\Service\Endpoint;

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
        
        $bikersEndpointService = new BikersEndpointService($bikerPostAction, $bikersGetAction);
        
        $returnedBiker = $bikersEndpointService->post('Renan Taranto', 'renantaranto@gmail.com');
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testGetReturnsBiker()
    {
        $bikerPostAction = $this->getMock(BikersPostActionInterface::class);
        
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $bikersGetAction = $this->getMock(BikersGetActionInterface::class);
        $bikersGetAction->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        
        $bikersEndpointService = new BikersEndpointService($bikerPostAction, $bikersGetAction);
        $returnedBiker = $bikersEndpointService->get(1);
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
}
