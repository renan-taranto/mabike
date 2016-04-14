<?php
namespace Tests\Rtaranto\Application\Service\Security;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Security\BikerRegistrationService;
use Rtaranto\Application\Service\Security\UserRegistrationInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class BikerRegistrationServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyRegisterBiker()
    {
        $username = 'username';
        $email = 'bikername@email.com';
        $password = 'pass12345';
        $biker = new Biker($username, $email);
        
        $userRegistrationService = $this->prophesize(UserRegistrationInterface::class);
        $user = $this->prophesize(User::class);
        $userRegistrationService->registerUser($username, $email, $password, array(User::ROLE_BIKER))
            ->willReturn($user);
        $biker->setUser($user->reveal());
        $bikerRepository = $this->prophesize(BikerRepositoryInterface::class);
        $bikerRepository->add($biker)->willReturn($biker);
        $validator = $this->prophesize(ValidatorInterface::class);
        
        $bikerRegistrationService = new BikerRegistrationService(
            $userRegistrationService->reveal(),
            $bikerRepository->reveal(),
            $validator->reveal()
        );
        $returnedBiker = $bikerRegistrationService->registerBiker($username, $email, $password);
        $this->assertInstanceOf(Biker::class, $returnedBiker);
    }
    
    public function testRegisterBikerThrowsValidationFailed()
    {
        $username = 'username';
        $email = 'bikername@email.com';
        $password = 'pass12345';
        $biker = new Biker($username, $email);
        
        $userRegistrationService = $this->prophesize(UserRegistrationInterface::class);
        $user = $this->prophesize(User::class);
        $userRegistrationService->registerUser($username, $email, $password, array(User::ROLE_BIKER))
            ->willReturn($user);
        $biker->setUser($user->reveal());
        $bikerRepository = $this->prophesize(BikerRepositoryInterface::class);
        
        $validator = $this->prophesize(ValidatorInterface::class);
        $validator->throwValidationFailedIfNotValid($biker)->willThrow(ValidationFailedException::class);
        
        $bikerRegistrationService = new BikerRegistrationService(
            $userRegistrationService->reveal(),
            $bikerRepository->reveal(),
            $validator->reveal()
        );
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $bikerRegistrationService->registerBiker($username, $email, $password);
    }
}
