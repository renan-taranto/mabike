<?php
namespace Rtaranto\Application\Service\Security;

use InvalidArgumentException;

class RandomKeyGenerator
{
    /**
     * @param integer $length
     * @return string
     */
    public static function generate($length = 255)
    {
        self::validateLength($length);
        
        return self::getRandomKey($length);
    }
    
    private static function validateLength($length)
    {
        if ($length < 1 or $length > 255) {
            throw new InvalidArgumentException('Length must be a value between'
                    . ' 1 and 255');
        }
    }
    
    private static function getRandomKey($length)
    {
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        
        $key = "";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i++) {
            $key .= $codeAlphabet[self::getRandomInteger(0, $max)];
        }
        
        return $key;
    }
    
    private static function getRandomInteger($min, $max)
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
