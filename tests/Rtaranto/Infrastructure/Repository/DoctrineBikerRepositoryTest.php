<?php
namespace Tests\Rtaranto\Infrastructure\Repository;

use AppBundle\DataFixtures\ORM\LoadMotorcycleTestingData;
use AppBundle\DataFixtures\ORM\LoadUserTestingData;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;

class DoctrineBikerRepositoryTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(LoadUserTestingData::class, LoadMotorcycleTestingData::class));
    }
    
    public function testSuccessfullyFindBiker()
    {
        $em = $this->getEntityManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $this->assertInstanceOf(Biker::class, $doctrineBikerRepository->get(1));
    }
    
    public function testFindBikerReturnsNull()
    {
        $em = $this->getEntityManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $this->assertNull($doctrineBikerRepository->get(232123));
    }
    
    public function testAddBiker()
    {
        $em = $this->getEntityManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $biker = new Biker('test user 2', 'testuser2@email.com');
        $doctrineBikerRepository->add($biker);
        $this->assertInstanceOf(Biker::class, $doctrineBikerRepository->get(3));
    }
    
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}
