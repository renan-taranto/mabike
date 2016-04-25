<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\PatchMotorcycleAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Motorcycle\MotorcyclePatcherInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatchMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPatchMotorcycle()
    {
        $id = 1;
        $newModel = 'model';
        $kmsDriven = 90000;
        $params = array('model' => $newModel, 'kms_driven'=> $kmsDriven);
        
        $currentModel = 'cur model';
        $motorcycle = new Motorcycle($currentModel);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->get($id)->willReturn($motorcycle);
        
        $motorcycleDTO = new MotorcycleDTO($currentModel);
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInputIgnoringMissingFields($params, $motorcycleDTO)
            ->willReturn($motorcycleDTO);
        
        $motorcyclePatcher = $this->prophesize(MotorcyclePatcherInterface::class);
        $motorcyclePatcher->patchMotorcycle($motorcycle, $motorcycleDTO)
            ->willReturn($motorcycle);
        
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $inputProcessor->reveal(),
            $motorcyclePatcher->reveal(),
            $motorcycleRepository->reveal()
        );
        
        
        $patchedMotorcycle = $patchMotorcycleAction->patch($id, $params);
        $this->assertInstanceOf(Motorcycle::class, $patchedMotorcycle);
    }
    
    public function testPatchMotorcycleWithInvalidParamsThrowsValidationFailed()
    {
        $id = 1;
        $model = 'm';
        $kmsDriven = -1;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $motorcycle = $this->prophesize(Motorcycle::class);
        $motorcycleRepository->get($id)->willReturn($motorcycle->reveal());
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInputIgnoringMissingFields($params, new MotorcycleDTO())
            ->willThrow(ValidationFailedException::class);
        
        $motorcyclePatcher = $this->prophesize(MotorcyclePatcherInterface::class);
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $inputProcessor->reveal(),
            $motorcyclePatcher->reveal(),
            $motorcycleRepository->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $patchMotorcycleAction->patch($id, $params);
    }
    
    public function testPatchMotorcycleWithInvalidBusinessRulesThrowsValidationFailed()
    {
        $id = 1;
        $model = 'm';
        $kmsDriven = -1;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $motorcycle = $this->prophesize(Motorcycle::class);
        $motorcycleRepository->get($id)->willReturn($motorcycle->reveal());
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInputIgnoringMissingFields($params, new MotorcycleDTO())->willReturn(new MotorcycleDTO());
        
        $motorcyclePatcher = $this->prophesize(MotorcyclePatcherInterface::class);
        $motorcyclePatcher->patchMotorcycle($motorcycle, new MotorcycleDTO())
            ->willThrow(ValidationFailedException::class);
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $inputProcessor->reveal(),
            $motorcyclePatcher->reveal(),
            $motorcycleRepository->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $patchMotorcycleAction->patch($id, $params);
    }
    
    public function testPatchMotorcycleThrowsNotFound()
    {
        $id = 1;
        $model = 'm';
        $kmsDriven = -1;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $motorcyclePatcher = $this->prophesize(MotorcyclePatcherInterface::class);
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $inputProcessor->reveal(),
            $motorcyclePatcher->reveal(),
            $motorcycleRepository->reveal()
        );
        
        $this->setExpectedException(NotFoundHttpException::class);
        $patchMotorcycleAction->patch($id, $params);
    }
}
