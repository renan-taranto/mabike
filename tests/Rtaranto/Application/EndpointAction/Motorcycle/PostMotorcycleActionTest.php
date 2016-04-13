<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class PostMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostMotorcycle()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $model = 'YBR';
        $kmsDriven = 43278;
        $motorcycle = new Motorcycle($model, $kmsDriven);
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $motorcycleRepository->expects($this->once())
            ->method('add')
            ->will($this->returnValue($motorcycle));
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue(new MotorcycleDTO()));
        $validator = $this->getMock(ValidatorInterface::class);
        
        $postMotorcycleAction = new PostMotorcycleAction($biker, $motorcycleRepository, $parametersBinder, $validator);
        $params = array('model' => $model, 'kmsDriven'=> $kmsDriven);
        $returnedMotorcycle = $postMotorcycleAction->post($params);
        
        $this->assertInstanceOf(Motorcycle::class, $returnedMotorcycle);
    }
    
    public function testPostMotorcycleThrowsValidationFailed()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue(new MotorcycleDTO()));
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->any())
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $postMotorcycleAction = new PostMotorcycleAction($biker, $motorcycleRepository, $parametersBinder, $validator);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postMotorcycleAction->post(array());
    }
    
    public function testPostMotorcycleWithInvalidBusinessRulesThrowsValidationFailed()
    {
        $biker = $this->getMockBuilder(Biker::class)
            ->disableOriginalConstructor()
            ->getMock();
        $motorcycleRepository = $this->getMock(MotorcycleRepositoryInterface::class);
        $parametersBinder = $this->getMock(ParametersBinderInterface::class);
        $parametersBinder->expects($this->once())
            ->method('bind')
            ->will($this->returnValue(new MotorcycleDTO()));
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->at(1))
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $postMotorcycleAction = new PostMotorcycleAction($biker, $motorcycleRepository, $parametersBinder, $validator);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postMotorcycleAction->post(array());
    }
}
