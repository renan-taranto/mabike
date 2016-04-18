<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

abstract class PerformedMaintenance
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var integer
     */
    protected $kmsDriven;
    
    /**
     * @var DateTime
     */
    protected $date;
    
    /**
     * @var Maintenance
     */
    protected $maintenance;
    
    /**
     * @return int
     */
    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
