<?php
namespace Tests\Rtaranto\Domain\Entity;

use DateTime;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;

class FrontTireChangeTest extends \PHPUnit_Framework_TestCase
{
    public function testChangeFrontTireReturnsPerformedFrontTireChange()
    {
        $motorcycle = new Motorcycle('model');
        $frontTireChange = new FrontTireChange($motorcycle);
        $kmsDriven = 2000;
        $date = new DateTime('2016-01-13');
        $performedFrontTireChange = $frontTireChange
            ->changeFrontTire($kmsDriven, $date);
        
        $this->assertInstanceOf(PerformedFrontTireChange::class, $performedFrontTireChange);
        $this->assertEquals($kmsDriven, $performedFrontTireChange->getKmsDriven());
        $this->assertEquals($date, $performedFrontTireChange->getDate());
    }
    
    public function testChangeFrontTireReturnsPerformedOilchangeWithCurDateAndCurKmsDriven()
    {
        $kmsDriven = 12324;
        $motorcycle = new Motorcycle('model', $kmsDriven);
        $frontTireChange = new FrontTireChange($motorcycle);
        
        $performedFrontTireChange = $frontTireChange
            ->changeFrontTire();
        
        $expectedDate = new DateTime('now');
        
        $this->assertEquals($kmsDriven, $performedFrontTireChange->getKmsDriven());
        $this->assertEquals($expectedDate, $performedFrontTireChange->getDate());
    }
}
