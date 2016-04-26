<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\FrontTireChangerService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPerformedFrontTireChangeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    private $frontTireChangerService;
    
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $sfValidator = $this->container->get('validator');
        $motorcycleRepository = new DoctrineMotorcycleRepository($em);
        $frontTireChangeRepository = new DoctrineMaintenanceRepository($em, FrontTireChange::class);
        $validator = new Validator($sfValidator);
        $this->frontTireChangerService = new FrontTireChangerService(
            $motorcycleRepository,
            $frontTireChangeRepository,
            $validator
        );
        
        $this->createPerformedFrontTireChanges();
    }
    
    private function createPerformedFrontTireChanges()
    {
        $data = $this->createPerformedFrontTireChangesAsArray();
        foreach($data as $d) {
            $performedMaintenanceDTO = new PerformedMaintenanceDTO($d['motorcycleId'], $d['kmsDriven'], $d['date']);
            $performedFrontTireChange = $this->
                frontTireChangerService->changeFrontTire($d['motorcycleId'], $performedMaintenanceDTO);
            $this->addReference($d['reference'], $performedFrontTireChange);
        }
    }
    
    public function createPerformedFrontTireChangesAsArray()
    {
        $ducatiId = $this->getReference('ducati')->getId();
        $xj6Id = $this->getReference('xj6')->getId();
        
        $performedFrontTireChanges = array();
        array_push($performedFrontTireChanges, array(
            'kmsDriven' => 0,
            'date'=> new DateTime('2016-01-10'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_front_tire_change_1'
        ));
        array_push($performedFrontTireChanges, array(
            'kmsDriven' => 1000,
            'date'=> new DateTime('2016-02-09'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_front_tire_change_2'
        ));
        array_push($performedFrontTireChanges, array(
            'kmsDriven' => 1100,
            'date'=> new DateTime('2016-03-09'),
            'motorcycleId' => $ducatiId,
            'reference' => 'performed_front_tire_change_3'
        ));
        array_push($performedFrontTireChanges, array(
            'kmsDriven' => 20000,
            'date'=> new DateTime('2016-04-09'),
            'motorcycleId' => $xj6Id,
            'reference' => 'performed_front_tire_change_4'
        ));
        array_push($performedFrontTireChanges, array(
            'kmsDriven' => 14000,
            'date'=> new DateTime('2016-01-09'),
            'motorcycleId' => $xj6Id,
            'reference' => 'performed_front_tire_change_5'
        ));
        
        return $performedFrontTireChanges;
    }
    
    public function getOrder()
    {
        return 5;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
