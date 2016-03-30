<?php
namespace AppBundle\DataFixtures\ORM;

use Rtaranto\Application\Service\Security\UserRegistrationService;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserTestingData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function load(ObjectManager $manager)
    {
        /* @var $userRegistration UserRegistrationService */
        $userRegistration = $this->container->get('app.user_registration');
        $user = $userRegistration->registerUser('test_user_1', 'testuser1@email.com', 123456);
        $datetime = new DateTime('now');
        $datetime->modify('+1 day');
        $user->updateApiKey('testuserkey', $datetime);
        $manager->persist($user);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
