<?php
namespace Application\Exception;

class ValidationFailedException extends \RuntimeException
{
    private $errors;
    
    public function __construct(array $errors)
    {
        parent::__construct('Validation Failed');
        $this->errors = $errors;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
