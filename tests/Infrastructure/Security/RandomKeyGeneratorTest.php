<?php
namespace Tests\Infrastructure\Security;

use Infrastructure\Security\RandomKeyGenerator;

class RandomKeyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateKey()
    {
        $key = RandomKeyGenerator::generate(250);
        
        $this->assertEquals(250, strlen($key));
        $this->assertInternalType('string', $key);
    }
    
    public function testGenerateMinimumLengthKey()
    {
        $key = RandomKeyGenerator::generate(1);
        
        $this->assertEquals(1, strlen($key));
        $this->assertInternalType('string', $key);
    }
    
    public function testBypassMinimumLength()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        RandomKeyGenerator::generate(-1);
    }
    
    public function testBypassMaximumLength()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        RandomKeyGenerator::generate(256);
    }
    
    public function testGenerateUniqueKey()
    {
        $key = RandomKeyGenerator::generate();
        $anotherKey = RandomKeyGenerator::generate();
        $yetAnotherKey = RandomKeyGenerator::generate();
        
        $this->assertNotEquals($key, $anotherKey);
        $this->assertNotEquals($key, $yetAnotherKey);
        $this->assertNotEquals($anotherKey, $yetAnotherKey);
    }
}
