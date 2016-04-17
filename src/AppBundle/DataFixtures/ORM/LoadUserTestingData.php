<?php
namespace AppBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Rtaranto\Domain\Entity\Biker;
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
        $this->loadUsersRoleUser($manager);
        $this->loadUsersRoleBiker($manager);
        $this->loadUsersRoleDev($manager);
    }
    
    private function loadUsersRoleUser(ObjectManager $manager)
    {
        $user = $this->createUser('test_user_3', 'testuser3@email.com', 12345678, array(User::ROLE_USER));
        $datetime = $this->createDateForApiKeyExpiration();
        $user->updateApiKey('testuser3key', $datetime);
        
        $manager->persist($user);
        $manager->flush();
        
        $this->addReference('user', $user);
    }
    
    private function loadUsersRoleBiker(ObjectManager $manager)
    {
        $datetime = $this->createDateForApiKeyExpiration();
        
        $user1 = $this->createUser('test_user_1', 'testuser1@email.com', 123456, array(User::ROLE_BIKER));
        $user1->updateApiKey('testuserkey', $datetime);
        $manager->persist($user1);
        
        $biker1 = new Biker('Test Biker', 'testbiker@email.com', $user1);
        $manager->persist($biker1);
        
        
        $user2 = $this->createUser('test_user_2', 'testuser2@email.com', 1234567, array(User::ROLE_BIKER));
        $user2->updateApiKey('testuser2key', $datetime);
        $manager->persist($user2);
        
        $biker2 = new Biker('Test Biker2', 'testbiker2@email.com', $user2);
        $manager->persist($biker2);
        
        $manager->flush();
        $this->addReference('biker_user_1', $user1);
        $this->addReference('biker1', $biker1);
        $this->addReference('biker_user_2', $user2);
    }
    
    private function loadUsersRoleDev(ObjectManager $manager)
    {
        $devUser = $this->createUser('dev_user', 'dev_user@email.com', 'dev_user_pass', array(User::ROLE_DEV));
        $datetime = $this->createDateForApiKeyExpiration();
        $devUser->updateApiKey('dev_user_key', $datetime);
        
        $manager->persist($devUser);
        $manager->flush();
        
        $this->addReference('dev_user', $devUser);
    }
    
    /**
     * @param string $name
     * @param string $email
     * @param string $pass
     * @param string $roles
     * @return User
     */
    private function createUser($name, $email, $pass, array $roles)
    {
        /* @var $userRegistration UserRegistrationService */
        $userRegistration = $this->container->get('app.user_registration');
        return $userRegistration->registerUser($name, $email, $pass, $roles);
    }

    private function createDateForApiKeyExpiration()
    {
        $datetime = new DateTime('now');
        $datetime->modify('+1 day');
        return $datetime;
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
