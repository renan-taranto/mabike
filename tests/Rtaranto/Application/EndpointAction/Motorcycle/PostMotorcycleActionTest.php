<?php
namespace Tests\Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\User;

class PostMotorcycleActionTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyPostMotorcycle()
    {
//        $username = 'username';
//        $email = 'useremail@email.com';
//        $password = 'pass';
//        $user = new User($username, $email, $password);
//        
//        $bikerName = 'biker';
//        $bikerEmail = 'biker@email.com';
//        $biker = new Biker($bikerName, $bikerEmail);
//        $biker->setUser($user);
//        
//        $postMotorcycleAction = new PostMotorcycleAction(new User($username, $email, $password));
//        
//        $model = 'YBR';
//        $kmsDriven = 43278;
//        $params = array('model' => $model, 'kmsDriven'=> $kmsDriven);
//        
//        $returnedMotorcycle = $postMotorcycleAction->post($params);
//        
//        $this->assertInstanceOf(Motorcycle::class, $returnedMotorcycle);
//        $this->assertEquals($model, $returnedMotorcycle->getModel());
//        $this->assertEquals($kmsDriven, $returnedMotorcycle->getKmsDriven());
//        
//        $returnedBiker = $returnedMotorcycle->getBiker();
//        $this->assertEquals($bikerName, $returnedBiker->getName());
    }
}
