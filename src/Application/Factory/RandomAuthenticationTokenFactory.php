<?php
namespace Application\Factory;

use Application\Dto\Security\AuthenticationToken;
use DateInterval;
use DateTime;
use Infrastructure\Security\RandomKeyGenerator;
use InvalidArgumentException;

class RandomAuthenticationTokenFactory implements AuthenticationTokenFactory
{
    /**
     * @param integer $keyLength
     * @param integer $minutesBeforeExpiration
     * @return AuthenticationTokenDTO
     */
    public function create($keyLength = 255, $minutesBeforeExpiration = 15)
    {
        $key = $this->createKey($keyLength);
        $expirationDateTime = $this->createExpirationDateTime($minutesBeforeExpiration);
        return new AuthenticationToken($key, $expirationDateTime);
    }
    
    /**
     * @param integer $keyLength
     * @return string
     */
    private function createKey($keyLength)
    {
        return RandomKeyGenerator::generate($keyLength);
    }
    
    /**
     * @param integer $minutesBeforeExpiration
     * @return DateTime
     */
    private function createExpirationDateTime($minutesBeforeExpiration)
    {
        $this->validateMinutesBeforeExpiration($minutesBeforeExpiration);
        $now = new DateTime('now');
        $interval = new DateInterval('PT' . $minutesBeforeExpiration . 'M');
        return $now->add($interval);
    }
    
    private function validateMinutesBeforeExpiration($minutesBeforeExpiration)
    {
        if ($minutesBeforeExpiration > 0) {
            return;
        }
        
        throw new InvalidArgumentException('Minutes before expiration must be'
            . ' greater than 0');
    }
}
