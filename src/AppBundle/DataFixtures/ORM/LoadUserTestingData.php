<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Rtaranto\Domain\Entity\User;
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
        
        $bikerUser1 = $userRegistration
            ->registerUser('test_user_1', 'testuser1@email.com', 123456, array(User::ROLE_BIKER));
        $bikerUser1->updateApiKey('testuserkey', $datetime);
        
        $bikerUser2 = $userRegistration
            ->registerUser('test_user_2', 'testuser2@email.com', 1234567, array(User::ROLE_BIKER));
        $bikerUser2->updateApiKey('testuser2key', $datetime);
        
        $user = $userRegistration
            ->registerUser('test_user_3', 'testuser3@email.com', 12345678, array(User::ROLE_USER));
        $user->updateApiKey('testuser3key', $datetime);
        
        $devUser = $userRegistration
            ->registerUser('dev_user', 'dev_user@email.com', 'dev_user_pass', array(User::ROLE_DEV));
        $devUser->updateApiKey('dev_user_key', $datetime);
        
        $manager->persist($bikerUser1);
        $manager->persist($bikerUser2);
        $manager->persist($user);
        $manager->persist($devUser);
        $manager->flush();
        
        $this->addReference('biker_user_1', $bikerUser1);
        $this->addReference('biker_user_2', $bikerUser2);
        $this->addReference('user', $user);
        $this->addReference('dev_user', $devUser);
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
