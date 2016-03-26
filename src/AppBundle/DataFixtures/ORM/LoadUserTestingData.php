<?php
namespace AppBundle\DataFixtures\ORM;

use Application\Service\RegisterUserService;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserTestingData implements FixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        /* @var $registerUserService RegisterUserService */
        $registerUserService = $this->container->get('app.service.register_user');
        $registerUserService->registerUser('test_user_1', 'testuser1@email.com', 123456);
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
