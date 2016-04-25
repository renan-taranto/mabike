<?php
namespace Rtaranto\Application\Dto\Maintenance;

class PerformedMaintenanceDTO
{
    /**
     * @var int
     */
    private $kmsDriven;

    /**
     * @var string
     */
    private $date;
    
    /**
     * @param int $kmsDriven
     * @param string $date
     */
    public function __construct($kmsDriven = null, $date = null)
    {
        $this->kmsDriven = $kmsDriven;
        $this->date = $this->convertDateToStringIfNeeded($date);
    }
    
    /**
     * @return int
     */
    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return new \DateTime($this->date);
    }

    /**
     * @param int $kmsDriven
     */
    public function setKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $this->convertDateToStringIfNeeded($date);
    }
    
    public function convertDateToStringIfNeeded($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        }
        return $date;
    }
}
