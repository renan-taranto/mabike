<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Service\Endpoint\Action\Biker\BikersCgetAction;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class BikersCgetActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsCollection()
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $bikers = array($biker, $aSecondBiker);
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue($bikers));
        
        $cgetAction = new BikersCgetAction($bikerRepository);
        $collection = $cgetAction->get();
        
        $this->assertEquals($bikers, $collection);
    }
}
