<?php
namespace Rtaranto\Domain\Entity;

class MaintenanceWarning
{
    private $id;
    private $description;
    private $atKms;
    
    public function __construct($description, $atKms)
    {
        $this->description = $description;
        $this->atKms = $atKms;
    }
    
    public function getAtKms()
    {
        return $this->atKms;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
}
