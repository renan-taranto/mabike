<?php
namespace Tests\Infrastructure\Repository;

use AppBundle\DataFixtures\ORM\LoadBikerTestingData;
use Domain\Entity\Biker;
use Infrastructure\Repository\DoctrineBikerRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DoctrineBikerRepositoryTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(LoadBikerTestingData::class));
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
        $this->assertNull($doctrineBikerRepository->get(2));
    }
    
    public function testAddBiker()
    {
        $em = $this->getEntityManager();
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $biker = new Biker('test user 2', 'testuser2@email.com');
        $doctrineBikerRepository->add($biker);
        $this->assertInstanceOf(Biker::class, $doctrineBikerRepository->get(2));
    }
    
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}
