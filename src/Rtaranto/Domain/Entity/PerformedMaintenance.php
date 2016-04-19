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
     * @var Motorcycle
     */
    protected $motorcycle;
    
    /**
     * @return int
     */
    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }
    
    public function getDate()
    {
        return $this->date;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    public function setKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
