<?php
namespace Tests\Rtaranto\Application\EndpointAction\OilChange;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadPerformedOilChangeData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Application\EndpointAction\OilChange\DeletePerformedOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeletePerformedOilChangeActionTest extends WebTestCase
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
    
    public function testSuccessfullyDeleteReturnsNull()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $doctrinePerformedOilChangeRepository = new DoctrinePerformedOilChangeRepository($em);
        
        $motorcycleId = 1;
        $performedOilChangeId = 1;
        $deletePerformedOilChangeAction = new DeletePerformedOilChangeAction($doctrinePerformedOilChangeRepository);
        $this->assertNull($deletePerformedOilChangeAction->delete($motorcycleId, $performedOilChangeId));
        $this->assertNull($doctrinePerformedOilChangeRepository
            ->findOneByMotorcycleAndId($motorcycleId, $performedOilChangeId));
    }
    
    public function testDeleteThrowsNotFound()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $doctrinePerformedOilChangeRepository = new DoctrinePerformedOilChangeRepository($em);
        
        $motorcycleId = 1;
        $performedOilChangeId = 1000;
        $deletePerformedOilChangeAction = new DeletePerformedOilChangeAction($doctrinePerformedOilChangeRepository);
        
        $this->setExpectedException(NotFoundHttpException::class);
        $deletePerformedOilChangeAction->delete($motorcycleId, $performedOilChangeId);
    }
}
