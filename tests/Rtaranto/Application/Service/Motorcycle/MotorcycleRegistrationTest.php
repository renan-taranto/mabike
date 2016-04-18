<?php
namespace Tests\Rtaranto\Application\Service\Motorcycle;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistrationService;
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
        $this->fixtures = $this->loadFixtures(array(LoadUserTestingData::class, LoadMotorcycleTestingData::class));
    }
    
    public function testRegisterMotorcycleReturnsMotorcycle()
    {
        /* @var $em EntityManagerInterface */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $sfValidator = $this->getContainer()->get('validator');
        $validator = new Validator($sfValidator);
        $motorcycleRegistration = new MotorcycleRegistrationService($validator, $em);
        
        $model = 'model';
        $kmsDriven = 2314;
        $biker = $this->fixtures->getReferenceRepository()->getReference('biker1');
        $motorcycle = $motorcycleRegistration->registerMotorcycle($biker, $model, $kmsDriven);
        
        $this->assertInstanceOf(Motorcycle::class, $motorcycle);
    }
    
    public function testRegisterInvalidMotorcycleThrowsException()
    {
        /* @var $em EntityManagerInterface */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $sfValidator = $this->getContainer()->get('validator');
        $validator = new Validator($sfValidator);
        $motorcycleRegistration = new MotorcycleRegistrationService($validator, $em);
        
        $model = 'model';
        $kmsDriven = -1;
        $biker = $this->fixtures->getReferenceRepository()->getReference('biker1');
        
        $this->setExpectedException(ValidationFailedException::class);
        $motorcycleRegistration->registerMotorcycle($biker, $model, $kmsDriven);
    }
}
