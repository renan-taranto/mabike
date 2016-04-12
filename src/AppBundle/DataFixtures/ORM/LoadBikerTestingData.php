<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;

class LoadBikerTestingData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $biker->setUser($this->getReference('user1'));
        $motorcycle = new Motorcycle('Ducati Hypermotard 796');
        $biker->addMotorcycle($motorcycle);
        
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $aSecondBiker->setUser($this->getReference('user2'));
        
        $manager->persist($biker);
        $manager->persist($aSecondBiker);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}

