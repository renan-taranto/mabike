<?php
namespace Rtaranto\Application\Dto\Motorcycle;

class MotorcycleDTO
{
    private $model;
    
    private $kmsDriven;
    
    /**
     * @param string $model
     * @param int $kmsDriven
     */
    public function __construct($model = null, $kmsDriven = null)
    {
        $this->model = $model;
        $this->kmsDriven = $kmsDriven;
    }
    
    public function getModel()
    {
        return $this->model;
    }

    public function getKmsDriven()
    {
        return $this->kmsDriven;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setKmsDriven($kmsDriven)
    {
        $this->kmsDriven = $kmsDriven;
    }
}
