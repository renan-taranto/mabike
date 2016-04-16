<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\GetMotorcycleAction;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMotorcyclesActionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReturnsMotorcycle()
    {
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycleRepository->get($id)->willReturn(new Motorcycle('model'));
        $getMotorcycleAction = new GetMotorcycleAction($motorcycleRepository->reveal());
        $motorcycle = $getMotorcycleAction->get($id);
        $this->assertInstanceOf(Motorcycle::class, $motorcycle);
    }
    
    public function testGetThrowsNotFound()
    {
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $getMotorcycleAction = new GetMotorcycleAction($motorcycleRepository->reveal());
        
        $this->setExpectedException(NotFoundHttpException::class);
        $getMotorcycleAction->get(1);
    }
}
