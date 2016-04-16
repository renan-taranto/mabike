<?php
namespace Tests\Rtaranto\Application\Service\Motorcycle;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistration;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;

class MotorcycleRegistrationTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadBikerTestingData::class));
    }
    
    public function testRegisterMotorcycleReturnsMotorcycle()
    {
        /* @var $em EntityManagerInterface */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        /* @var $maintenancePerformerRepository MaintenancePerformerRepositoryInterface */
        $maintenancePerformerRepository = $em->getRepository(MaintenancePerformer::class);
        $sfValidator = $this->getContainer()->get('validator');
        $validator = new Validator($sfValidator);
        $motorcycleRegistration = new MotorcycleRegistration($validator, $motorcycleRepository, $maintenancePerformerRepository);
        
        $model = 'model';
        $kmsDriven = 2314;
        $biker = $this->fixtures->getReferenceRepository()->getReference('biker1');
        $motorcycle = $motorcycleRegistration->registerMotorcycle($biker, $model, $kmsDriven);
        
        $this->assertInstanceOf(Motorcycle::class, $motorcycle);
        $maintenancePerformer = $maintenancePerformerRepository->findByMotorcycle($motorcycle);
        $this->assertInstanceOf(MaintenancePerformer::class, $maintenancePerformer);
    }
    
    public function testRegisterInvalidMotorcycleThrowsException()
    {
        /* @var $em EntityManagerInterface */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        /* @var $maintenancePerformerRepository MaintenancePerformerRepositoryInterface */
        $maintenancePerformerRepository = $em->getRepository(MaintenancePerformer::class);
        $sfValidator = $this->getContainer()->get('validator');
        $validator = new Validator($sfValidator);
        $motorcycleRegistration = new MotorcycleRegistration($validator, $motorcycleRepository, $maintenancePerformerRepository);
        
        $model = 'model';
        $kmsDriven = -1;
        $biker = $this->fixtures->getReferenceRepository()->getReference('biker1');
        
        $this->setExpectedException(ValidationFailedException::class);
        $motorcycleRegistration->registerMotorcycle($biker, $model, $kmsDriven);
    }
}
