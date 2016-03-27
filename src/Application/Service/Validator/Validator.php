<?php
namespace Application\Service\Validator;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

/*
 * Wrapper for Symfony's Validator Component.
 * It helps decoupling application code from framework code while customizing
 * errors array
 */
class Validator implements ValidatorInterface
{
    private $symfonyValidatorComponent;
    
    public function __construct(SymfonyValidatorInterface $symfonyValidatorComponent)
    {
        $this->symfonyValidatorComponent = $symfonyValidatorComponent;
    }
    
    public function isValid($object)
    {
        $errors = $this->symfonyValidatorComponent->validate($object);
        
        if(count($errors) > 0) {
            return false;
        }
        return true;
    }
    
    public function getErrors($object)
    {
        if ($this->isValid($object)) {
            throw new Exception('Object is valid and has no errors');
        }
        
        $constraintViolationList = $this->symfonyValidatorComponent->validate($object);
        $errorMessagesByFields = $this->getErrorMessagesByFields($constraintViolationList);
        
        return array_merge(array('message' => 'Validation Failed'), array('errors' => $errorMessagesByFields));
    }
    
    private function getErrorMessagesByFields(ConstraintViolationListInterface $constraintViolationList)
    {
        $errorMessagesByFields = array();
        $fields = array();
        
        for ($i = 0; $i < $constraintViolationList->count(); $i++) {
            $propertyName = $constraintViolationList->get($i)->getPropertyPath();
            if (!in_array($propertyName, $fields)) {
                array_push($fields, $propertyName);
                $errorMessagesByFields[$propertyName] = array();
            }
        }
        
        for ($i = 0; $i < $constraintViolationList->count(); $i++) {
            $propertyName = $constraintViolationList->get($i)->getPropertyPath();
            $errorMessagesByFields[$propertyName] = array_merge(
                $errorMessagesByFields[$propertyName],
                array($constraintViolationList->get($i)->getMessage()));
        }
        return $errorMessagesByFields;
    }
}
