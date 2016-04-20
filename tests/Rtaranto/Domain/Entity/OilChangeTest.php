<?php
namespace Tests\Rtaranto\Domain\Entity;

use DateTime;
use Exception;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\OilChange;

class OilChangeTest extends \PHPUnit_Framework_TestCase
{
    public function testChangeOilReturnsOilChange()
    {
        $motorcycle = $this->prophesize(Motorcycle::class)->reveal();
        $oilChangeMaintenance = new OilChange($motorcycle, 1500);
        $oilChange = $oilChangeMaintenance->changeOil(1234, new DateTime('2016-01-19'));
        $this->assertInstanceOf(PerformedOilChange::class, $oilChange);
    }
    
    public function testGetKmsForNextOilChangeReturnsKms()
    {
        $motorcycle = $this->prophesize(Motorcycle::class)->reveal();
        $oilChangeMaintenance = new OilChange($motorcycle);
        $oilChangeMaintenance->setKmsPerMaintenance(1500);
        $oilChangeMaintenance->changeOil(4560, new DateTime('2016-05-19'));
        $oilChangeMaintenance->changeOil(0, new DateTime('2016-01-19'));
        $oilChangeMaintenance->changeOil(2565, new DateTime('2016-04-19'));
        $oilChangeMaintenance->changeOil(1234, new DateTime('2016-03-19'));
        
        $kmsForNextOilChange = $oilChangeMaintenance->getKmsForNextMaintenance();
        $expectedKmsForNextOilChange = 6060;
        
        $this->assertEquals($expectedKmsForNextOilChange, $kmsForNextOilChange);
    }
    
    public function testGetKmsForNextOilChangeWithNoPreviousOilChangeThrowsException()
    {
        $motorcycle = $this->prophesize(Motorcycle::class)->reveal();
        $oilChangeMaintenance = new OilChange($motorcycle, 1500);
        $this->setExpectedException(Exception::class);
        $oilChangeMaintenance->getKmsForNextMaintenance();
    }
    
    public function testGetKmsForNextOilChangeWithoutSettingKmsPerOilChangeThrowsException()
    {
        $motorcycle = $this->prophesize(Motorcycle::class)->reveal();
        $oilChangeMaintenance = new OilChange($motorcycle);
        $this->setExpectedException(Exception::class);
        $oilChangeMaintenance->getKmsForNextMaintenance();
    }
}
