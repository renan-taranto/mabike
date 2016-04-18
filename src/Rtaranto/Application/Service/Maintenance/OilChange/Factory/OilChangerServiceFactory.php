<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangerService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Application\Service\Validator\ValidatorInterface as ValidatorInterface2;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OilChangerServiceFactory implements OilChangerServiceFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ValidatorInterface2
     */
    private $validator;
    
    public function __construct(EntityManagerInterface $em, ValidatorInterface $sfValidator)
    {
        $this->em = $em;
        $this->validator = new Validator($sfValidator);
    }
    
    /**
     * @return OilChangerService
     */
    public function createOilChangerService()
    {
        $maintenancePerformerRepository = $this->em->getRepository(MaintenancePerformer::class);
        return new OilChangerService($this->validator, $maintenancePerformerRepository);
    }

}
