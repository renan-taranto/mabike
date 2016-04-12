<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;

class LoadBikerTestingData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $user = $manager->getRepository('Domain:User')->find(1);
        $biker->setUser($user);
        $motorcycle = new Motorcycle('Ducati Hypermotard 796');
        $biker->addMotorcycle($motorcycle);
        
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $user3 = $manager->getRepository('Domain:User')->find(3);
        $aSecondBiker->setUser($user3);
        
        $manager->persist($biker);
        $manager->persist($aSecondBiker);
        $manager->flush();
    }
}

