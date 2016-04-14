<?php
namespace Rtaranto\Application\Command\Security;

use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\Service\Security\BikerRegistrationServiceInterface;
use Rtaranto\Domain\Entity\Biker;

class BikerRegistrationCommand
{
    private $bikerRegistrationService;
    
    /**
     * @param BikerRegistrationServiceInterface $bikerRegistrationService
     */
    public function __construct(BikerRegistrationServiceInterface $bikerRegistrationService)
    {
        $this->bikerRegistrationService = $bikerRegistrationService;
    }
    
    /**
     * @param UserRegistrationDTO $userRegistrationDTO
     * @return Biker
     */
    public function execute(UserRegistrationDTO $userRegistrationDTO)
    {
        $biker = $this->bikerRegistrationService->registerBiker(
            $userRegistrationDTO->getUsername(),
            $userRegistrationDTO->getEmail(),
            $userRegistrationDTO->getPassword()
        );
        
        return $biker;
    }
}
