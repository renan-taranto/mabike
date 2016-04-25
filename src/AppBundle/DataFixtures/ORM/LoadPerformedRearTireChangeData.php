<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\RearTireChangerService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPerformedRearTireChangeData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
        /**
         * @var ContainerInterface
         */
        private $container;

        private $rearTireChangerService;

        public function load(ObjectManager $manager)
        {
            $em = $this->container->get('doctrine.orm.entity_manager');
            $sfValidator = $this->container->get('validator');
            $rearTireChangeRepository = new DoctrineMaintenanceRepository($em, RearTireChange::class);
            $validator = new Validator($sfValidator);
            $this->rearTireChangerService = new RearTireChangerService($rearTireChangeRepository, $validator);

            $this->createPerformedRearTireChanges();
        }

        private function createPerformedRearTireChanges()
        {
            $data = $this->createPerformedRearTireChangesAsArray();
            foreach($data as $d) {
                $performedMaintenanceDTO = new PerformedMaintenanceDTO($d['kmsDriven'], $d['date']);
                $performedRearTireChange = $this->rearTireChangerService
                    ->changeRearTire($d['motorcycleId'], $performedMaintenanceDTO);
                $this->addReference($d['reference'], $performedRearTireChange);
            }
        }

        public function createPerformedRearTireChangesAsArray()
        {
            $ducatiId = $this->getReference('ducati')->getId();
            $xj6Id = $this->getReference('xj6')->getId();

            $performedRearTireChanges = array();
            array_push($performedRearTireChanges, array(
                'kmsDriven' => 0,
                'date'=> new DateTime('2016-01-10'),
                'motorcycleId' => $ducatiId,
                'reference' => 'performed_rear_tire_change_1'
            ));
            array_push($performedRearTireChanges, array(
                'kmsDriven' => 900,
                'date'=> new DateTime('2016-02-09'),
                'motorcycleId' => $ducatiId,
                'reference' => 'performed_rear_tire_change_2'
            ));
            array_push($performedRearTireChanges, array(
                'kmsDriven' => 1300,
                'date'=> new DateTime('2016-03-09'),
                'motorcycleId' => $ducatiId,
                'reference' => 'performed_rear_tire_change_3'
            ));
            array_push($performedRearTireChanges, array(
                'kmsDriven' => 18000,
                'date'=> new DateTime('2016-04-09'),
                'motorcycleId' => $xj6Id,
                'reference' => 'performed_rear_tire_change_4'
            ));
            array_push($performedRearTireChanges, array(
                'kmsDriven' => 13000,
                'date'=> new DateTime('2016-01-09'),
                'motorcycleId' => $xj6Id,
                'reference' => 'performed_rear_tire_change_5'
            ));

            return $performedRearTireChanges;
        }

        public function getOrder()
        {
            return 4;
        }

        public function setContainer(ContainerInterface $container = null)
        {
            $this->container = $container;
        }
}
