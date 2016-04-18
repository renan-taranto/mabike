<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

class MaintenancePerformer implements OilChangerInterface
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var OilChangeMaintenance
     */
    private $oilChangeMaintenance;
    
    /**
     * @var Motorcycle
     */
    private $motorcycle;
    
    public function __construct(Motorcycle $motorcycle)
    {
        $this->motorcycle = $motorcycle;
        $this->oilChangeMaintenance = new OilChangeMaintenance($motorcycle);
    }

    public function changeOil($kmsDriven, DateTime $date = null)
    {
        return $this->oilChangeMaintenance->changeOil($kmsDriven, $date);
    }
    
    public function getOilChangeMaintenance()
    {
        return $this->oilChangeMaintenance;
    }


}
