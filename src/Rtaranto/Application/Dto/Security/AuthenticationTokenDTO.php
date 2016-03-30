<?php
namespace Rtaranto\Application\Dto\Security;

use DateTime;

class AuthenticationTokenDTO
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
