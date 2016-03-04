<?php

namespace AppBundle\Entity;

use DateTime;

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
    private $brand;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $color;

    /**
     * @var DateTime
     */
    private $year;
    
    /**
     * @var int
     */
    private $kilometersDriven;
    
    public function __construct(
        $brand,
        $model,
        $color,
        DateTime $year,
        $kilometersDriven = 0
    ) {
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->year = $year;
        $this->kilometersDriven = $kilometersDriven;
    }
}

