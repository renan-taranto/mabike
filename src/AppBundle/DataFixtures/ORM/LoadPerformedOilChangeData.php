<?php

namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\OilChange\Factory\OilChangerServiceFactory;
use Rtaranto\Application\Service\Maintenance\OilChange\OilChangerServiceInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPerformedOilChangeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var OilChangerServiceInterface
     */
    private $oilChangerService;
    
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $sfValidator = $this->container->get('validator');
        $oilChangerServiceFactory = new OilChangerServiceFactory($em, $sfValidator);
        $this->oilChangerService = $oilChangerServiceFactory->createOilChangerService();
        
        $this->createPerformedOilChanges();
    }
    
    private function createPerformedOilChanges()
    {
        $data = $this->createPerformedOilChangesAsArray();
        foreach($data as $d) {
            $performedMaintenanceDTO = new PerformedMaintenanceDTO($d['kmsDriven'], $d['date']);
            $performedOilChange = $this->oilChangerService->changeOil($d['motorcycleId'], $performedMaintenanceDTO);
            $this->addReference($d['reference'], $performedOilChange);
        }
    }
    
    public function createPerformedOilChangesAsArray()
    {
        $motorcycleId = $this->getReference('ducati')->getId();
        
        $oilChanges = array();
        array_push($oilChanges, array(
            'kmsDriven' => 0,
            'date'=> new DateTime('2016-01-09'),
            'motorcycleId' => $motorcycleId,
            'reference' => 'performed_oil_change_1'
        ));
        array_push($oilChanges, array(
            'kmsDriven' => 800,
            'date'=> new DateTime('2016-02-09'),
            'motorcycleId' => $motorcycleId,
            'reference' => 'performed_oil_change_2'
        ));
        array_push($oilChanges, array(
            'kmsDriven' => 1200,
            'date'=> new DateTime('2016-03-09'),
            'motorcycleId' => $motorcycleId,
            'reference' => 'performed_oil_change_3'
        ));
        
        return $oilChanges;
    }
    
    public function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
