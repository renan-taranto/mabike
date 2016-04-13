<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\CgetMotorcyclesAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;

class CgetMotorcyclesActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsMotorcyclesCollection()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $motorcycle1 = new Motorcycle('Ducati Hypermotard 796');
        $motorcycle2 = new Motorcycle('XT 660X');
        $motorcycles = array($motorcycle1, $motorcycle2);
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->expects($this->once())
                ->method('findAllByBiker')
                ->will($this->returnValue($motorcycles));
        $queryParamsFetcher = $this->getMock(QueryParamsFetcherInterface::class);
        
        $cGetMotorcyclesAction = new CgetMotorcyclesAction($biker, $motorcycleRepository, $queryParamsFetcher);
        
        $expectedReturn = array($motorcycle1, $motorcycle2);
        $actualReturn = $cGetMotorcyclesAction->cGet();
        $this->assertEquals($expectedReturn, $actualReturn);
    }
    
    public function testCgetUserNotRelatedToBikerReturnsEmptyArray()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->expects($this->once())
                ->method('findAllByBiker')
                ->will($this->returnValue(array()));
        $queryParamsFetcher = $this->getMock(QueryParamsFetcherInterface::class);
        
        $cGetMotorcyclesAction = new CgetMotorcyclesAction($biker, $motorcycleRepository, $queryParamsFetcher);
        
        $expectedReturn = array();
        $actualReturn = $cGetMotorcyclesAction->cGet();
        $this->assertEquals($expectedReturn, $actualReturn);
    }
}
