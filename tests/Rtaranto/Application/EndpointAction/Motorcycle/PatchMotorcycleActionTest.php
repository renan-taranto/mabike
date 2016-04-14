<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\Motorcycle\PatchMotorcycleAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class PatchMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPatchMotorcycle()
    {
        $model = 'model';
        $kmsDriven = 90000;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $persistedMotorcycle = new Motorcycle('persisted bike', 3456);
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->willReturn($persistedMotorcycle);
        $motorcycleRepository->update($persistedMotorcycle)->willReturn(new Motorcycle($model, $kmsDriven));
        
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $validator = $this->prophesize(ValidatorInterface::class);
        
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $motorcycleRepository->reveal(),
            $biker->reveal(),
            $parametersBinder->reveal(),
            $validator->reveal()
        );
        
        $patchedMotorcycle = $patchMotorcycleAction->patch(1, $params);
        $this->assertInstanceOf(Motorcycle::class, $patchedMotorcycle);
    }
    
    public function testPatchMotorcycleWithInvalidParamsThrowsValidationFailed()
    {
        $model = 'm';
        $kmsDriven = -1;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $persistedBikeModel = 'persisted bike';
        $persistedBikeKmsDriven = 5456;
        $persistedMotorcycle = new Motorcycle($persistedBikeModel, $persistedBikeKmsDriven);
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->willReturn($persistedMotorcycle);
        
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid(new MotorcycleDTO($persistedBikeModel, $persistedBikeKmsDriven))
            ->willThrow(ValidationFailedException::class);
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $motorcycleRepository->reveal(),
            $biker->reveal(),
            $parametersBinder->reveal(),
            $validator->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $patchMotorcycleAction->patch(1, $params);
    }
    
    public function testPatchMotorcycleWithInvalidBusinessRulesThrowsValidationFailed()
    {
        $model = 'm';
        $kmsDriven = -1;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $persistedBikeModel = 'persisted bike';
        $persistedBikeKmsDriven = 5456;
        $persistedMotorcycle = new Motorcycle($persistedBikeModel, $persistedBikeKmsDriven);
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->willReturn($persistedMotorcycle);
        
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid(new MotorcycleDTO($persistedBikeModel, $persistedBikeKmsDriven))
            ->shouldBeCalled();
        $validator->throwValidationFailedIfNotValid(new MotorcycleDTO($persistedBikeModel, $persistedBikeKmsDriven))
            ->willThrow(ValidationFailedException::class);
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $motorcycleRepository->reveal(),
            $biker->reveal(),
            $parametersBinder->reveal(),
            $validator->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $patchMotorcycleAction->patch(1, $params);
    }
    
    public function testPatchMotorcycleThrowsNotFound()
    {
        $model = 'model';
        $kmsDriven = 90000;
        $params = array('model' => $model, 'kms_driven'=> $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $motorcycleRepository = $this->prophesize(MotorcycleRepositoryInterface::class);
        $id = 1;
        $motorcycleRepository->findOneByBikerAndId($biker, $id)->willThrow(ValidationFailedException::class);
        
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $validator = $this->prophesize(ValidatorInterface::class);
        
        
        $patchMotorcycleAction = new PatchMotorcycleAction(
            $motorcycleRepository->reveal(),
            $biker->reveal(),
            $parametersBinder->reveal(),
            $validator->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $patchMotorcycleAction->patch(1, $params);
    }
}
