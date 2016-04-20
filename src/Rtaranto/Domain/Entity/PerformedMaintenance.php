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
     * @param Motorcycle $motorcycle
     * @param int $kmsDriven
     * @param DateTime $date
     */
    public function __construct(Motorcycle $motorcycle, $kmsDriven, DateTime $date)
    {
        $this->motorcycle = $motorcycle;
        $this->kmsDriven = $kmsDriven;
        $this->date = $date;
    }
    
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
