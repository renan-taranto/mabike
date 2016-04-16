<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\DeleteMotorcycleAction;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyDeleteMotorcycleReturnsNull()
    {
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycle = $this->prophesize(Motorcycle::class);
        $motorcycleRepository->get($id)->willReturn($motorcycle->reveal());
        $motorcycleRepository->delete($id)->shouldBeCalled();
        $deleteMotorcycleAction = new DeleteMotorcycleAction($motorcycleRepository->reveal());
        $this->assertNull($deleteMotorcycleAction->delete($id));
    }
    
    public function testDeleteNotFoundMotorcycleThrowsException()
    {
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycleRepository->get($id)->shouldBeCalled();
        $deleteMotorcycleAction = new DeleteMotorcycleAction($motorcycleRepository->reveal());
        $this->setExpectedException(NotFoundHttpException::class);
        $deleteMotorcycleAction->delete($id);
    }
}
