<?php
namespace AppBundle\Security;

use DateTime;

class TokenCommand
{
    /**
     * @var string
     */
    private $key;
    
    /**
     * @var DateTime
     */
    private $expirationDateTime;
    
    public function __construct($key, $expirationDateTime)
    {
        $this->key = $key;
        $this->expirationDateTime = $expirationDateTime;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function getExpirationDateTime()
    {
        return $this->expirationDateTime;
    }
}
