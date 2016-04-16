<?php
namespace Tests\Rtaranto\Application\EndpointAction\OilChange;

use DateTime;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\OilChange\PostOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\OilChangeMaintenance;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;

class BikerPostOilChangeActionTest extends \PHPUnit_Framework_TestCase
{
    public function testPostReturnsOilChange()
    {
        $motorcycleId = 1;
        $kmsDriven = 1234;
        $date = new DateTime('2016-03-04');
        $params = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $maintenanceDTO = new PerformedMaintenanceDTO($kmsDriven, $date);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind($params, new PerformedMaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        
        $maintenancePerformer = $this->prophesize(MaintenancePerformer::class);
        $oilChange = new PerformedOilChange($kmsDriven, $date);
        $maintenancePerformer->changeOil($kmsDriven, $date)->willReturn($oilChange);
        $maintenancePerformerRepository = $this->prophesize(MaintenancePerformerRepositoryInterface::class);
        $maintenancePerformerRepository->findByMotorcycle($motorcycleId)->willReturn($maintenancePerformer->reveal());
        $maintenancePerformerRepository->update($maintenancePerformer)->shouldBeCalled();
        
        $bikerPostOilChangeAction = new PostOilChangeAction(
            $parametersBinder->reveal(),
            $validator->reveal(),
            $maintenancePerformerRepository->reveal()
        );
        
        $returnedOilChange = $bikerPostOilChangeAction->post($motorcycleId, $params);
        $this->assertInstanceOf(PerformedOilChange::class, $returnedOilChange);
    }
    
    public function testPostBadParamsThrowsValidationFailed()
    {
        $maintenanceDTO = new PerformedMaintenanceDTO(null, null);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind(array(), new PerformedMaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->willThrow(ValidationFailedException::class);
        
        $oilChangePerformerRepository = $this->prophesize(MaintenancePerformerRepositoryInterface::class);
        
        $bikerPostOilChangeAction = new PostOilChangeAction(
            $parametersBinder->reveal(),
            $validator->reveal(),
            $oilChangePerformerRepository->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostOilChangeAction->post(1, array());
    }
    
    public function testPostBusinessRulesViolationsThrowsValidationFailed()
    {
        $motorcycleId = 1;
        $kmsDriven = 1234;
        $date = new DateTime('2016-03-04');
        $params = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $maintenanceDTO = new PerformedMaintenanceDTO($kmsDriven, $date);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind($params, new PerformedMaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->shouldBeCalled();
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->willThrow(ValidationFailedException::class);
        
        $oilChangePerformer = $this->prophesize(OilChangeMaintenance::class);
        $oilChange = new PerformedOilChange($kmsDriven, $date);
        $oilChangePerformer->changeOil($kmsDriven, $date)->willReturn($oilChange);
        $oilChangePerformerRepository = $this->prophesize(MaintenancePerformerRepositoryInterface::class);
        $oilChangePerformerRepository->findByMotorcycle($motorcycleId)->willReturn($oilChangePerformer->reveal());
        
        $bikerPostOilChangeAction = new PostOilChangeAction(
            $parametersBinder->reveal(),
            $validator->reveal(),
            $oilChangePerformerRepository->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostOilChangeAction->post($motorcycleId, $params);
    }
}
