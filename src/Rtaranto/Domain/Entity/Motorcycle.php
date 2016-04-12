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
    
    public function __construct($model, $kilometersDriven = 0) {
        $this->model = $model;
        $this->kmsDriven = $kilometersDriven;
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

