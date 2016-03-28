<?php
namespace Tests\Application\Service\Endpoint\Action\Biker;

use Application\Service\Endpoint\Action\Biker\BikersPutAction;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersPutActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPutBiker()
    {
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        
        $biker = new Biker('anyname', 'anyemail');
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue($biker));
        $bikerRepository->expects($this->once())
            ->method('update')
            ->will($this->returnValue($biker));
        
        $expectedBiker = new Biker('new name', 'new email');
        $bikersPutAction = new BikersPutAction($bikerRepository, $validator);
        $returnedBiker = $bikersPutAction->put(1, 'new name', 'new email');
        $this->assertEquals($expectedBiker, $returnedBiker);
    }
    
    public function testPutBikerThrowsNotFoundException()
    {
        $validator = $this->getMock(ValidatorInterface::class);
        $bikerRepository = $this->getMock(BikerRepository::class);
        $bikerRepository->expects($this->once())
            ->method('get')
            ->will($this->returnValue(null));
        $bikersPutAction = new BikersPutAction($bikerRepository, $validator);
        $this->setExpectedException(NotFoundHttpException::class);
        $returnedBiker = $bikersPutAction->put(1, 'new name', 'new email');
    }
}
