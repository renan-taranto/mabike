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
        
        $datetime = new DateTime('now');
        $datetime->modify('+1 day');
        
        $user = $userRegistration->registerUser('test_user_1', 'testuser1@email.com', 123456);
        $user->updateApiKey('testuserkey', $datetime);
        
        $user2 = $userRegistration->registerUser('test_user_2', 'testuser2@email.com', 1234567);
        $user2->updateApiKey('testuser2key', $datetime);
        
        $user3 = $userRegistration->registerUser('test_user_3', 'testuser3@email.com', 12345678);
        $user3->updateApiKey('testuser3key', $datetime);
        
        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
