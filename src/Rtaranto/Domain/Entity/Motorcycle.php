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

    /**
     * @param int $model
     * @param int $kmsDriven
     */
    public function __construct($model, $kmsDriven = 0)
    {
        $this->model = $model;
        
        if (empty($kmsDriven)) {
            $kmsDriven = 0;
        }
        $this->kmsDriven = $kmsDriven;
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $kmsDriven
     */
    public function updateKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }
    
    /**
     * @return int
     */
    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }
    
    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Biker $biker
     */
    public function setBiker(Biker $biker)
    {
        $this->biker = $biker;
    }
}
