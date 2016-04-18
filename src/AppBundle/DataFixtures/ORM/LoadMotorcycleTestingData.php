<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistrationService;
use Rtaranto\Application\Service\Validator\Validator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadMotorcycleTestingData extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $sfValidator = $this->container->get('validator');
        $validator = new Validator($sfValidator);
        $motorcycleRegistrationService = new MotorcycleRegistrationService($validator, $em);
        
        $biker = $this->getReference('biker1');
        $model = 'Ducati Hypermotard 796';
        $kmsDriven = 1560;
        $motorcycle = $motorcycleRegistrationService->registerMotorcycle($biker, $model, $kmsDriven);
        
        $this->addReference('ducati', $motorcycle);
    }

    public function getOrder()
    {
        return 2;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}

