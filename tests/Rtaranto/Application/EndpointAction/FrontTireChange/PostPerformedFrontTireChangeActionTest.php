<?php
namespace Tests\Rtaranto\Application\EndpointAction\FrontTireChange;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use DateTime;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Application\EndpointAction\FrontTireChange\PostPerformedFrontTireChangeAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;

class PostPerformedFrontTireChangeActionTest extends WebTestCase
{
    private $fixtures;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            LoadUserTestingData::class,
            LoadMotorcycleTestingData::class,
        ));
    }
    
    public function testSucessfullyPostReturnsPerformedFrontTireChange()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $motorcyleId = 1;
        $kmsDriven = 1000;
        $date = '2016-01-10';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        
        $postPerformedFrontTireChangeAction = $this->getPostAction();
        $performedFrontTireChange = $postPerformedFrontTireChangeAction->post($motorcyleId, $data);
        
        $this->assertInstanceOf(PerformedFrontTireChange::class, $performedFrontTireChange);
        
        $subResourceRepository = new DoctrineSubResourceRepository($em, 'motorcycle', PerformedFrontTireChange::class);
        $performedFrontTireChangeFromRepository = $subResourceRepository->
            findOneByParentResourceAndId($motorcyleId, $performedFrontTireChange->getId());
        
        $this->assertInstanceOf(PerformedFrontTireChange::class, $performedFrontTireChangeFromRepository);
        $this->assertEquals($performedFrontTireChangeFromRepository->getKmsDriven(), $kmsDriven);
        $this->assertEquals($performedFrontTireChangeFromRepository->getDate()->format('Y-m-d'), $date);
    }
    
    public function testPostWithBlankParamsReturnsPerformedFrontTireChangeWithCurKmsDrivenAndCurDate()
    {
        $postPerformedFrontTireChangeAction = $this->getPostAction();
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $data = array();
        $performedRearTireChange = $postPerformedFrontTireChangeAction->post($motorcyleId, $data);
        
        $this->assertInstanceOf(PerformedFrontTireChange::class, $performedRearTireChange);
        
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $subResourceRepository = new DoctrineSubResourceRepository($em, 'motorcycle', PerformedFrontTireChange::class);
        $performedFrontTireChangeFromRepository = $subResourceRepository->
            findOneByParentResourceAndId($motorcyleId, $performedRearTireChange->getId());
        
        $expectedKmsDriven = $motorcycle->getKmsDriven();
        $curDate = new DateTime('now');
        $expectedDate = $curDate->format('Y-m-d');
        $this->assertInstanceOf(PerformedFrontTireChange::class, $performedFrontTireChangeFromRepository);
        $this->assertEquals($expectedKmsDriven, $performedFrontTireChangeFromRepository->getKmsDriven());
        $this->assertEquals($expectedDate, $performedFrontTireChangeFromRepository->getDate()->format('Y-m-d'));
    }
    
    public function testPostWithInvalidDateThrowsValidationFailed()
    {
        $postPerformedFrontTireChangeAction = $this->getPostAction();
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $kmsDriven = 8000;
        $date = 'invalid date';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postPerformedFrontTireChangeAction->post($motorcyleId, $data);
    }
    
    public function testPostWithInvalidKmsThrowsValidationFailed()
    {
        $postPerformedFrontTireChangeAction = $this->getPostAction();
        
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->fixtures->getReferenceRepository()->getReference('ducati');
        $motorcyleId = $motorcycle->getId();
        $kmsDriven = -1;
        $date = '2015-01-21';
        $data = array('kmsDriven' => $kmsDriven, 'date' => $date);
        
        $this->setExpectedException(ValidationFailedException::class);
        $postPerformedFrontTireChangeAction->post($motorcyleId, $data);
    }
    
    private function getPostAction()
    {
        /* @var $postPerformedFrontTireChangeAction PostPerformedFrontTireChangeAction */
        return $postPerformedFrontTireChangeAction = $this->getContainer()->get('app.performed_front_tire_change.post_action');
    }
}
