<?php
namespace Tests\Rtaranto\Application\EndpointAction\OilChange;

use DateTime;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\OilChange\PostOilChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangerInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangerServiceInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;

class PostOilChangeActionTest extends \PHPUnit_Framework_TestCase
{
    public function testPostReturnsOilChange()
    {
        $motorcycleId = 1;
        $kmsDriven = 1234;
        $date = new DateTime('2016-03-04');
        $params = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput($params, new PerformedMaintenanceDTO)
            ->willReturn(new PerformedMaintenanceDTO());
                
        $oilChangePoster = $this->prophesize(OilChangerServiceInterface::class);
        $performedOilChange = $this->prophesize(PerformedOilChange::class);
        $oilChangePoster->changeOil($motorcycleId, new PerformedMaintenanceDTO())
            ->willReturn($performedOilChange->reveal());
        
        $postOilChangeAction = new PostOilChangeAction($inputProcessor->reveal(), $oilChangePoster->reveal());
        
        $this->assertInstanceOf(PerformedOilChange::class, $postOilChangeAction->post($motorcycleId, $params));
    }
    
    public function testPostBadParamsThrowsValidationFailed()
    {
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput(array(), new PerformedMaintenanceDTO)
            ->willThrow(ValidationFailedException::class);
        
        $oilChangePoster = $this->prophesize(OilChangerServiceInterface::class);
        
        $bikerPostOilChangeAction = new PostOilChangeAction($inputProcessor->reveal(), $oilChangePoster->reveal());
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostOilChangeAction->post(1, array());
    }
    
    public function testPostBusinessRulesViolationsThrowsValidationFailed()
    {
        $motorcycleId = 1;
        $kmsDriven = 1234;
        $date = new DateTime('2016-03-04');
        $params = array('kms_driven' => $kmsDriven, 'date' => $date);
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput($params, new PerformedMaintenanceDTO)
            ->willReturn(new PerformedMaintenanceDTO());
        
        $oilChangePoster = $this->prophesize(OilChangerServiceInterface::class);
        $oilChangePoster->changeOil($motorcycleId, new PerformedMaintenanceDTO())
            ->willThrow(ValidationFailedException::class);
        
        $bikerPostOilChangeAction = new PostOilChangeAction($inputProcessor->reveal(), $oilChangePoster->reveal());
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerPostOilChangeAction->post(1, $params);
    }
}
