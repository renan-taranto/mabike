<?php
namespace Rtaranto\Domain\Entity;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    private $roles;
    
    /**
     * @var string
     */
    private $apiKey;
    
    /**
     * @var DateTime
     */
    private $apiKeyExpirationTime;

    /**
     * @var string
     */
    private $password;
    
    
    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->roles = array('ROLE_USER');
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @param DateTime $apiKeyExpirationTime
     */
    public function updateApiKey($apiKey, $apiKeyExpirationTime)
    {
        $this->apiKey = $apiKey;
        $this->apiKeyExpirationTime = $apiKeyExpirationTime;
    }

    /**
     * Get apiKeyExpirationTime
     *
     * @return DateTime
     */
    public function getApiKeyExpirationTime()
    {
        return $this->apiKeyExpirationTime;
    }
    
    public function eraseCredentials()
    {
        
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        if (!in_array(strtoupper($role), $this->roles)) {
            array_push($this->roles, strtoupper($role));
        }
    }
    
    public function getRoles()
    {
        if (empty($this->roles)) {
            return array('ROLE_USER');
        }
        
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }
}