<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserTestingData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        
        $this->addReference('user1', $user);
        $this->addReference('user2', $user2);
        $this->addReference('user3', $user3);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }

}
