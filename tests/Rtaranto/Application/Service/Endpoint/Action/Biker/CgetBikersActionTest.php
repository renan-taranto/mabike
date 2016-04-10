<?php
namespace Tests\Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\EndpointAction\Biker\CgetBikersAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetBikersActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsCollection()
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
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
