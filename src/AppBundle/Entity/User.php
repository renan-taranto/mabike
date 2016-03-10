<?php

namespace AppBundle\Entity;

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
     * @var \DateTime
     */
    private $apiKeyExpirationTime;

    /**
     * @var string
     */
    private $password;
    
    
    public function __construct(array $roles)
    {
        $this->roles = $roles;
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
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
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
     * Set apiKeyExpirationTime
     *
     * @param \DateTime $apiKeyExpirationTime
     *
     * @return User
     */
    public function setApiKeyExpirationTime($apiKeyExpirationTime)
    {
        $this->apiKeyExpirationTime = $apiKeyExpirationTime;

        return $this;
    }

    /**
     * Get apiKeyExpirationTime
     *
     * @return \DateTime
     */
    public function getApiKeyExpirationTime()
    {
        return $this->apiKeyExpirationTime;
    }
    
    public function eraseCredentials()
    {
        
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }
}
