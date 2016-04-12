<?php
namespace Rtaranto\Application\Dto\Security;

class UserRegistrationDTO
{
    private $username;
    private $email;
    private $password;
    private $roles;
    
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }
    
    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}
