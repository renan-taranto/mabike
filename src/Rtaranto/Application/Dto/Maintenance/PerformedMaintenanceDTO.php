<?php
namespace Rtaranto\Application\Dto\Maintenance;

class PerformedMaintenanceDTO
{
    /**
     * @var int
     */
    private $motorcycleId;
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
    public function __construct($motorcycleId, $kmsDriven = null, $date = null)
    {
        $this->motorcycleId = $motorcycleId;
        $this->kmsDriven = $kmsDriven;
        $this->date = $this->convertDateToStringIfNeeded($date);
    }
    
    public function getMotorcycleId()
    {
        return $this->motorcycleId;
    }

    public function setMotorcycleId($motorcycleId)
    {
        $this->motorcycleId = $motorcycleId;
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

    public function getDateAsString()
    {
        return $this->convertDateToStringIfNeeded($this->date);
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
