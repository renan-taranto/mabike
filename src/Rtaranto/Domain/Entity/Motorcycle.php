<?php

namespace Rtaranto\Domain\Entity;

use JMS\Serializer\Annotation\Exclude;

/**
 * Motorcycle
 */
class Motorcycle
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var string
     */
    private $model;
    
    /**
     * @var int
     */
    private $kmsDriven;
    
    /**
     * @Exclude
     * @var Biker
     */
    private $biker;
    
    public function __construct($model, $kmsDriven = 0)
    {
        $this->model = $model;
        
        if (empty($kmsDriven)) {
            $kmsDriven = 0;
        }
        $this->kmsDriven = $kmsDriven;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function updateKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }
    
    public function setBiker(Biker $biker)
    {
        $this->biker = $biker;
    }
}

