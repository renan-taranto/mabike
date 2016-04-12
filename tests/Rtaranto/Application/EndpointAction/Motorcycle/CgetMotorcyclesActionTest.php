<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\CgetMotorcyclesAction;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CgetMotorcyclesActionTest extends \PHPUnit_Framework_TestCase
{
    public function testCgetReturnsMotorcyclesCollection()
    {
        $user = $this->getMock(UserInterface::class);
        $motorcycle1 = new Motorcycle('Ducati Hypermotard 796');
        $motorcycle2 = new Motorcycle('XT 660X');
        $motorcycles = array($motorcycle1, $motorcycle2);
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->expects($this->once())
                ->method('findAllByUser')
                ->will($this->returnValue($motorcycles));
        $queryParamsFetcher = $this->getMock(QueryParamsFetcherInterface::class);
        
        $cGetMotorcyclesAction = new CgetMotorcyclesAction($user, $motorcycleRepository, $queryParamsFetcher);
        
        $expectedReturn = array($motorcycle1, $motorcycle2);
        $actualReturn = $cGetMotorcyclesAction->cGet();
        $this->assertEquals($expectedReturn, $actualReturn);
    }
    
    public function testCgetUserNotRelatedToBikerReturnsEmptyArray()
    {
        $user = $this->getMock(UserInterface::class);
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->expects($this->once())
                ->method('findAllByUser')
                ->will($this->returnValue(array()));
        $queryParamsFetcher = $this->getMock(QueryParamsFetcherInterface::class);
        
        $cGetMotorcyclesAction = new CgetMotorcyclesAction($user, $motorcycleRepository, $queryParamsFetcher);
        
        $expectedReturn = array();
        $actualReturn = $cGetMotorcyclesAction->cGet();
        $this->assertEquals($expectedReturn, $actualReturn);
    }
}
