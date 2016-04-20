<?php
namespace Tests\Rtaranto\Application\EndpointAction\RearTireChange;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Application\EndpointAction\RearTireChange\PostPerformedRearTireChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;

class PostPerformedRearTireChangeActionTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
            LoadPerformedOilChangeData::class
        ));
    }
    
    public function testSucessfullyPostReturnsPerformedRearTireChange()
    {
        /* @var $postPerformedRearTireChangeAction PostPerformedRearTireChangeAction */
        $postPerformedRearTireChangeAction = $this->getContainer()->get('app.performed_rear_tire_change.post_action');
        
        $motorcyleId = 1;
        $kmsDriven = 8000;
        $date = '2016-01-10';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        $performedRearTireChange = $postPerformedRearTireChangeAction->post($motorcyleId, $data);
        
        $this->assertInstanceOf(PerformedRearTireChange::class, $performedRearTireChange);
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $subResourceRepository = new DoctrineSubResourceRepository($em, 'motorcycle', PerformedRearTireChange::class);
        $performedRearTireChangeFromRepository = $subResourceRepository->
            findOneByParentResourceAndId($motorcyleId, $performedRearTireChange->getId());
        
        $this->assertInstanceOf(PerformedRearTireChange::class, $performedRearTireChangeFromRepository);
        $this->assertEquals($performedRearTireChangeFromRepository->getKmsDriven(), $kmsDriven);
        $this->assertEquals($performedRearTireChangeFromRepository->getDate()->format('Y-m-d'), $date);
    }
    
    public function testPostWithBlankParamsReturnsPerformedRearTireChangeWithCurKmsDrivenAndCurDate()
    {
        /* @var $postPerformedRearTireChangeAction PostPerformedRearTireChangeAction */
        $postPerformedRearTireChangeAction = $this->getContainer()->get('app.performed_rear_tire_change.post_action');
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $data = array();
        $performedRearTireChange = $postPerformedRearTireChangeAction->post($motorcyleId, $data);
        
        $this->assertInstanceOf(PerformedRearTireChange::class, $performedRearTireChange);
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $subResourceRepository = new DoctrineSubResourceRepository($em, 'motorcycle', PerformedRearTireChange::class);
        $performedRearTireChangeFromRepository = $subResourceRepository->
            findOneByParentResourceAndId($motorcyleId, $performedRearTireChange->getId());
        
        $expectedKmsDriven = $motorcycle->getKmsDriven();
        $curDate = new DateTime('now');
        $expectedDate = $curDate->format('Y-m-d');
        $this->assertInstanceOf(PerformedRearTireChange::class, $performedRearTireChangeFromRepository);
        $this->assertEquals($expectedKmsDriven, $performedRearTireChangeFromRepository->getKmsDriven());
        $this->assertEquals($expectedDate, $performedRearTireChangeFromRepository->getDate()->format('Y-m-d'));
    }
    
    public function testPostWithInvalidDateThrowsValidationFailed()
    {
        /* @var $postPerformedRearTireChangeAction PostPerformedRearTireChangeAction */
        $postPerformedRearTireChangeAction = $this->getContainer()->get('app.performed_rear_tire_change.post_action');
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $kmsDriven = 8000;
        $date = 'invalid date';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postPerformedRearTireChangeAction->post($motorcyleId, $data);
    }
    
    public function testPostWithInvalidKmsThrowsValidationFailed()
    {
        /* @var $postPerformedRearTireChangeAction PostPerformedRearTireChangeAction */
        $postPerformedRearTireChangeAction = $this->getContainer()->get('app.performed_rear_tire_change.post_action');
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $kmsDriven = -1;
        $date = '2015-01-21';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postPerformedRearTireChangeAction->post($motorcyleId, $data);
    }
}
