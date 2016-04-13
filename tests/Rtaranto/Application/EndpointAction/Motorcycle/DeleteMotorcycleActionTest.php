<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\DeleteMotorcycleAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyDeleteMotorcycleReturnsNull()
    {
        $biker = $this->prophesize(Biker::class);
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->willReturn($biker);
        $motorcycleRepository->delete($id)->shouldBeCalled();
        $deleteMotorcycleAction = new DeleteMotorcycleAction($biker->reveal(), $motorcycleRepository->reveal());
        $this->assertNull($deleteMotorcycleAction->delete($id));
    }
    
    public function testDeleteNotFoundMotorcycleThrowsException()
    {
        $biker = $this->prophesize(Biker::class);
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->shouldBeCalled();
        $deleteMotorcycleAction = new DeleteMotorcycleAction($biker->reveal(), $motorcycleRepository->reveal());
        $this->setExpectedException(NotFoundHttpException::class);
        $deleteMotorcycleAction->delete($id);
    }
}
