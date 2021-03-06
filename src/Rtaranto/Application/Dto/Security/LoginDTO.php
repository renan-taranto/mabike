<?php
namespace Rtaranto\Application\Dto\Security;

class LoginDTO
{
    /**
     * @var string
     */
    private $username;
    
    /**
     * @var string
     */
    private $password;
    
    
    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
