<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Domain\Entity\Biker;

class LoadBikerTestingData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $biker = new Biker('Test Biker', 'testbiker@email.com');
        $aSecondBiker = new Biker('Test Biker2', 'testbiker2@email.com');
        $manager->persist($biker);
        $manager->persist($aSecondBiker);
        $manager->flush();
    }
}

