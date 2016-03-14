<?php
namespace AppBundle\Security;

use DateInterval;
use DateTime;
use Domain\Entity\Repository\UserRepository;
use InvalidArgumentException;

class TokenGenerator
{
    private $userRepository;
    
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * @param integer $length
     * @param integer $minutesBeforeExpiration
     * @return TokenCommand
     */
    public function generate($length = 255, $minutesBeforeExpiration = 15)
    {
        $this->validateLength($length);
        $this->validateMinutesBeforeExpiration($minutesBeforeExpiration);
        
        while (true) {
            $key = $this->getRandomKey($length);
            if (empty($this->userRepository->findByApiKey($key))) {
                $expirationDateTime = $this->getExpirationDateTime($minutesBeforeExpiration);
                return new TokenCommand($key, $expirationDateTime);
            }
        }
    }
    
    private function validateLength($length)
    {
        if ($length < 1) {
            throw new InvalidArgumentException('Length must be greater than 0');
        }
    }
    
    private function validateMinutesBeforeExpiration($minutesBeforeExpiration)
    {
        if ($minutesBeforeExpiration < 1) {
            throw new InvalidArgumentException('Minutes before expiration must be greater than 0');
        }
    }
    
    private function getRandomKey($length)
    {
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        
        $key = "";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $key .= $codeAlphabet[$this->getRandomInteger(0, $max)];
        }
        
        return $key;
    }
    
    private function getExpirationDateTime($minutesBeforeExpiration)
    {
        $time = new DateTime('now');
        $time->add(new DateInterval('PT' . $minutesBeforeExpiration . 'M'));

        return $time;
    }
    
    private function getRandomInteger($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits        
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        
        return $min + $rnd;
    }
}
