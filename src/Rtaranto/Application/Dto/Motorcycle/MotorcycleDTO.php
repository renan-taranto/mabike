<?php
namespace Rtaranto\Application\Dto\Motorcycle;

class MotorcycleDTO
{
    private $model;
    
    private $kmsDriven;

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
