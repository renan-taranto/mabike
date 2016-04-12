<?php
namespace Rtaranto\Domain\Entity;

use Exception;
use Rtaranto\Domain\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatingUserWithoutRolesThrowsException()
    {
        $roles = array();
        $this->setExpectedException(Exception::class);
        new User('username', 'email', 'pass', $roles);
    }
}
