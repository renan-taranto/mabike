<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Motorcycle;

class LoadMotorcycleTestingData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $model = 'Ducati Hypermotard 796';
        $motorcycle = new Motorcycle($model, 1560);
        $maintenancePerformer = new MaintenancePerformer($motorcycle);
  
        $biker = $this->getReference('biker1');
        $biker->addMotorcycle($motorcycle);
        
        $manager->persist($biker);
        $manager->persist($motorcycle);
        $manager->persist($maintenancePerformer);
        $manager->flush();
        $this->addReference('ducati', $motorcycle);
    }

    public function getOrder()
    {
        return 2;
    }
}

