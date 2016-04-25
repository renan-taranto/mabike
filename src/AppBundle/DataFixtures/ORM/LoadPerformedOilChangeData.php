<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\OilChangerService;
use Rtaranto\Application\Service\Maintenance\OilChangerServiceInterface;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
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
        $validator = new Validator($sfValidator);
        
        $oilChangeRepository = new DoctrineMaintenanceRepository($em, OilChange::class);
        $this->oilChangerService = new OilChangerService($oilChangeRepository, $validator);
        
        $this->createPerformedOilChanges();
    }
    
    private function createPerformedOilChanges()
    {
        $data = $this->createPerformedOilChangesAsArray();
        foreach($data as $d) {
            $performedMaintenanceDTO = new PerformedMaintenanceDTO($d['motorcycleId'], $d['kmsDriven'], $d['date']);
            $performedOilChange = $this->oilChangerService->changeOil($d['motorcycleId'], $performedMaintenanceDTO);
            $this->addReference($d['reference'], $performedOilChange);
        }
    }
    
    public function createPerformedOilChangesAsArray()
    {
        $ducatiId = $this->getReference('ducati')->getId();
        $xj6Id = $this->getReference('xj6')->getId();
        
        $performedOilChanges = array();
        array_push($performedOilChanges, array(
            'kmsDriven' => 0,
            'date'=> new DateTime('2016-01-09'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_oil_change_1'
        ));
        array_push($performedOilChanges, array(
            'kmsDriven' => 800,
            'date'=> new DateTime('2016-02-09'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_oil_change_2'
        ));
        array_push($performedOilChanges, array(
            'kmsDriven' => 1200,
            'date'=> new DateTime('2016-03-09'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_oil_change_3'
        ));
        array_push($performedOilChanges, array(
            'kmsDriven' => 20000,
            'date'=> new DateTime('2016-04-09'),
            'motorcycleId' => $xj6Id,
            'reference' => 'performed_oil_change_4'
        ));
        array_push($performedOilChanges, array(
            'kmsDriven' => 14000,
            'date'=> new DateTime('2016-01-09'),
            'motorcycleId' => $xj6Id,
            'reference' => 'performed_oil_change_5'
        ));
        
        return $performedOilChanges;
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
