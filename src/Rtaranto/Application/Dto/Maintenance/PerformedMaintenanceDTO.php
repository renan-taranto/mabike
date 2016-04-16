<?php
namespace Rtaranto\Application\Dto\Maintenance;

class PerformedMaintenanceDTO
{
    /**
     * @var int
     */
    private $kmsDriven;
    
    /**
     * @var \DateTime
     */
    private $date;
    
    /**
     * @param int $kmsDriven
     * @param \DateTime $date
     */
    public function __construct($kmsDriven = null, \DateTime $date = null)
    {
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

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $kmsDriven
     */
    public function setKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }
}
