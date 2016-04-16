<?php
namespace Tests\Rtaranto\Application\EndpointAction\OilChange;

use DateTime;
use Rtaranto\Application\Dto\Maintenance\MaintenanceDTO;
use Rtaranto\Application\EndpointAction\OilChange\BikerPostOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangePerformer;
use Rtaranto\Domain\Entity\Repository\OilChangePerformerRepositoryInterface;

class BikerPostOilChangeActionTest extends \PHPUnit_Framework_TestCase
{
    public function testPostReturnsOilChange()
    {
        $motorcycleId = 1;
        $kmsDriven = 1234;
        $date = new DateTime('2016-03-04');
        $params = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $maintenanceDTO = new MaintenanceDTO($kmsDriven, $date);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind($params, new MaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        
        $oilChangePerformer = $this->prophesize(OilChangePerformer::class);
        $oilChange = new OilChange($kmsDriven, $date);
        $oilChangePerformer->changeOil($kmsDriven, $date)->willReturn($oilChange);
        $oilChangePerformerRepository = $this->prophesize(OilChangePerformerRepositoryInterface::class);
        $oilChangePerformerRepository->findByMotorcycle($motorcycleId)->willReturn($oilChangePerformer->reveal());
        $oilChangePerformerRepository->update($oilChangePerformer)->shouldBeCalled();
        
        $bikerPostOilChangeAction = new BikerPostOilChangeAction(
            $parametersBinder->reveal(),
            $validator->reveal(),
            $oilChangePerformerRepository->reveal()
        );
        
        $returnedOilChange = $bikerPostOilChangeAction->post($motorcycleId, $params);
        $this->assertInstanceOf(OilChange::class, $returnedOilChange);
    }
    
    public function testPostBadParamsThrowsValidationFailed()
    {
        $maintenanceDTO = new MaintenanceDTO(null, null);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind(array(), new MaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->willThrow(ValidationFailedException::class);
        
        $oilChangePerformerRepository = $this->prophesize(OilChangePerformerRepositoryInterface::class);
        
        $bikerPostOilChangeAction = new BikerPostOilChangeAction(
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
        
        $maintenanceDTO = new MaintenanceDTO($kmsDriven, $date);
        $parametersBinder = $this->prophesize(ParametersBinderInterface::class);
        $parametersBinder->bind($params, new MaintenanceDTO())->willReturn($maintenanceDTO);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->shouldBeCalled();
        $validator->throwValidationFailedIfNotValid($maintenanceDTO)->willThrow(ValidationFailedException::class);
        
        $oilChangePerformer = $this->prophesize(OilChangePerformer::class);
        $oilChange = new OilChange($kmsDriven, $date);
        $oilChangePerformer->changeOil($kmsDriven, $date)->willReturn($oilChange);
        $oilChangePerformerRepository = $this->prophesize(OilChangePerformerRepositoryInterface::class);
        $oilChangePerformerRepository->findByMotorcycle($motorcycleId)->willReturn($oilChangePerformer->reveal());
        
        $bikerPostOilChangeAction = new BikerPostOilChangeAction(
            $parametersBinder->reveal(),
            $validator->reveal(),
            $oilChangePerformerRepository->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostOilChangeAction->post($motorcycleId, $params);
    }
}
