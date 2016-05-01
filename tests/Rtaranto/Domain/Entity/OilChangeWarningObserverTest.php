<?php
namespace Tests\Rtaranto\Domain\Entity;

use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Rtaranto\Domain\Entity\MaintenanceWarning;

class OilChangeWarningObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testObserverWontTriggerIfNoMaintenanceHasBeenPerformed()
    {
        $motorcycle = new Motorcycle('model');
        $oilChange = new OilChange($motorcycle, 1500);
        
        $oilChangeObserver = new OilChangeWarningObserver($motorcycle, $oilChange, 100);
        $oilChangeObserver->activate();
        $motorcycle->attachMaintenanceWarningObserver($oilChangeObserver);
        
        $motorcycle->updateKmsDriven(1400);
        
        $warnings = $motorcycle->getWarnings();
        $this->assertEmpty($warnings);
    }
    
    public function testObserverTriggerWarningForZeroKmMotorcycle()
    {
        $motorcycle = new Motorcycle('model');
        $oilChange = new OilChange($motorcycle, 1500);
        
        $oilChange->changeOil();
        
        $oilChangeObserver = new OilChangeWarningObserver($motorcycle, $oilChange, 100);
        $oilChangeObserver->activate();
        $motorcycle->attachMaintenanceWarningObserver($oilChangeObserver);
        
        $motorcycle->updateKmsDriven(1400);
        
        $warning = $motorcycle->getWarnings()[0];
        $this->assertInstanceOf(MaintenanceWarning::class, $warning);
    }
    
    public function testObserverTriggerWarningFromPreviousOilChange()
    {
        $motorcycle = new Motorcycle('model', 43243);
        $oilChange = new OilChange($motorcycle, 1500);
        
        $oilChange->changeOil(41016);
        
        $oilChangeObserver = new OilChangeWarningObserver($motorcycle, $oilChange, 100);
        $oilChangeObserver->activate();
        $motorcycle->attachMaintenanceWarningObserver($oilChangeObserver);
        
        $warning = $motorcycle->getWarnings()[0];
        $this->assertInstanceOf(MaintenanceWarning::class, $warning);
    }
    
    public function testObserverTriggerWarningAfterOilChange()
    {
        $motorcycle = new Motorcycle('model', 43243);
        $oilChange = new OilChange($motorcycle, 1500);
        
        $oilChangeObserver = new OilChangeWarningObserver($motorcycle, $oilChange, 100);
        $oilChangeObserver->activate();
        $motorcycle->attachMaintenanceWarningObserver($oilChangeObserver);
        
        $oilChange->changeOil(41016);
        
        $warning = $motorcycle->getWarnings()[0];
        $this->assertInstanceOf(MaintenanceWarning::class, $warning);
    }
}
