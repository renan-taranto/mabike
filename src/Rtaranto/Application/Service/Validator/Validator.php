<?php
namespace Rtaranto\Application\Service\Validator;

use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Response;
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
    
    public function throwValidationFailedIfNotValid($object)
    {
        if (!$this->isValid($object)) {
            $errors = $this->getErrors($object);
            throw new ValidationFailedException($errors);
        }    
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
            return;
        }
        
        $constraintViolationList = $this->symfonyValidatorComponent->validate($object);
        $errorMessagesByFields = $this->getErrorMessagesByFields($constraintViolationList);
        
        return array_merge(array('code' => Response::HTTP_BAD_REQUEST,'message' => 'Validation Failed'), array('errors' => $errorMessagesByFields));
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
