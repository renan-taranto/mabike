<?php
namespace Rtaranto\Application\Factory;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Infrastructure\Security\RandomKeyGenerator;
use DateInterval;
use DateTime;
use InvalidArgumentException;

class AuthenticationTokenFactory implements AuthenticationTokenFactoryInterface
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
        return new AuthenticationTokenDTO($key, $expirationDateTime);
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
