<?php
namespace Tests\Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\Biker\CgetBikersAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetBikersActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsCollection()
    {
        $user = $this->prophesize(User::class);
        $biker = new Biker('Test Biker', 'testbiker@email.com', $user->reveal());
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com', $user->reveal());
        $bikers = array($biker, $aSecondBiker);
        $bikerRepository = $this->getMock(BikerRepositoryInterface::class);
        $bikerRepository->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue($bikers));
        
        $queryParamFetcher = $this->getMock(QueryParamsFetcherInterface::class);
        
        $cgetAction = new CgetBikersAction($bikerRepository, $queryParamFetcher);
        $collection = $cgetAction->cGet();
        
        $this->assertEquals($bikers, $collection);
    }
}
