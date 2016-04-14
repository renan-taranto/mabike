<?php
namespace Rtaranto\Application\Command\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Domain\Entity\Motorcycle;

class PatchMotorcycleCommand
{
    private $motorcycle;
    
    public function __construct(Motorcycle $motorcycle)
    {
        $this->motorcycle = $motorcycle;
    }
    
    public function execute(MotorcycleDTO $motorcycleDTO)
    {
        $kmsDriven = $motorcycleDTO->getKmsDriven();
        if (!empty($kmsDriven)) {
            $this->motorcycle->updateKmsDriven($kmsDriven);
        }
        
        $model = $motorcycleDTO->getModel();
        if (!empty($model)) {
            $this->motorcycle->setModel($model);
        }
    }
}
