<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class KmsDoNotExceedCurKmsDriven extends Constraint
{
    public $message = 'Kms exceeds current motorcycle kms driven.'
        . ' Update motorcycle kms driven if needed before trying again.';
    protected $motorcycleId;
    
    public function __construct($options = null)
    {
        parent::__construct($options);
        if (!$options['motorcycleId']) {
            throw new MissingOptionsException('Missing "motorcycleId" option.', array('motorcycleId'));
        }
        $this->motorcycleId = $options['motorcycleId'];
    }
    
    public function getMotorcycleId()
    {
        return $this->motorcycleId;
    }
    
    public function validatedBy()
    {
        return 'kms_do_not_exceeds_cur_kms_driven';
    }
    
}
