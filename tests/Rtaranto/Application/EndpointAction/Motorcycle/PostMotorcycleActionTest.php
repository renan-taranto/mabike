<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistrationInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;

class PostMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostMotorcycle()
    {
        $model = 'a valid model';
        $kmsDriven = 23124;
        $motorcycleDTO = new MotorcycleDTO($model, $kmsDriven);
        
        $data = array('model' => $model, 'kms_driven' => $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $motorcycleRegistration = $this->prophesize(MotorcycleRegistrationInterface::class);
        $motorcycle = new Motorcycle($model, $kmsDriven);
        $motorcycleRegistration->registerMotorcycle($biker->reveal(), $model, $kmsDriven)->willReturn($motorcycle);
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput($data, new MotorcycleDTO())->willReturn($motorcycleDTO);
        
        $postMotorcycleAction = new PostMotorcycleAction(
            $biker->reveal(),
            $motorcycleRegistration->reveal(),
            $inputProcessor->reveal()
        );
        
        $returnedMotorcycle = $postMotorcycleAction->post($data);
        
        $this->assertInstanceOf(Motorcycle::class, $returnedMotorcycle);
    }
    
    public function testPostBadParamsThrowsValidationFailed()
    {
        $model = '';
        $kmsDriven = -1;
        $data = array('model' => $model, 'kms_driven' => $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput($data, new MotorcycleDTO())->willThrow(ValidationFailedException::class);
        $motorcycleRegistration = $this->prophesize(MotorcycleRegistrationInterface::class);
        
        $postMotorcycleAction = new PostMotorcycleAction(
            $biker->reveal(),
            $motorcycleRegistration->reveal(),
            $inputProcessor->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        $postMotorcycleAction->post($data);
    }
    
    public function testPostMotorcycleWithInvalidBusinessRulesThrowsValidationFailed()
    {
        $model = 'a invalid motorcycle model';
        $kmsDriven = -1;
        $motorcycleDTO = new MotorcycleDTO($model, $kmsDriven);
        $data = array('model' => $model, 'kms_driven' => $kmsDriven);
        
        $biker = $this->prophesize(Biker::class);
        
        $inputProcessor = $this->prophesize(InputProcessorInterface::class);
        $inputProcessor->processInput($data, new MotorcycleDTO())->willReturn($motorcycleDTO);
        
        $motorcycleRegistration = $this->prophesize(MotorcycleRegistrationInterface::class);
        $motorcycleRegistration->registerMotorcycle($biker->reveal(), $model, $kmsDriven)
            ->willThrow(ValidationFailedException::class);
        
        $postMotorcycleAction = new PostMotorcycleAction(
            $biker->reveal(),
            $motorcycleRegistration->reveal(),
            $inputProcessor->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $postMotorcycleAction->post($data);
    }
}
