<?php
namespace Rtaranto\Domain\Entity;

use DateTime;

abstract class Maintenance
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
