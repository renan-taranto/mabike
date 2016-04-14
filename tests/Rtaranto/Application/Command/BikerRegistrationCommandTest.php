<?php
namespace Tests\Rtaranto\Application\Command;

use Rtaranto\Application\Command\Security\BikerRegistrationCommand;
use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\Service\Security\BikerRegistrationService;
use Rtaranto\Domain\Entity\Biker;

class BikerRegistrationCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandReturnsBiker()
    {
        $biker = $this->prophesize(Biker::class)->reveal();
        $bikerRegistrationService = $this->prophesize(BikerRegistrationService::class);
        $userRegistrationDTO = $this->prophesize(UserRegistrationDTO::class)->reveal();
        $bikerRegistrationService->registerBiker(null, null, null)->willReturn($biker);
        $bikerRegistrationCommand = new BikerRegistrationCommand($bikerRegistrationService->reveal());
        
        
        $returnedBiker = $bikerRegistrationCommand->execute($userRegistrationDTO);
        
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
}
