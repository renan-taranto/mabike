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
    /**
     * @var SymfonyValidatorInterface
     */
    protected $symfonyValidator;
    
    public function __construct(SymfonyValidatorInterface $sfValidator)
    {
        $this->symfonyValidator = $sfValidator;
    }
    
    /**
     * @param object $object
     * @throws ValidationFailedException
     */
    public function throwValidationFailedIfNotValid($object)
    {
        if (!$this->isValid($object)) {
            $errors = $this->getErrors($object);
            throw new ValidationFailedException($errors);
        }    
    }
    
    protected function isValid($object)
    {
        $errors = $this->validate($object);
        
        if(count($errors) > 0) {
            return false;
        }
        return true;
    }
    
    protected function validate($object)
    {
        return $this->symfonyValidator->validate($object);
    }
    
    protected function getErrors($object)
    {        
        $constraintViolationList = $this->validate($object);
        $errorMessagesByFields = $this->getErrorMessagesByFields($constraintViolationList);
        
        return array_merge(array('code' => Response::HTTP_BAD_REQUEST,'message' => 'Validation Failed'), array('errors' => $errorMessagesByFields));
    }
    
    protected function getErrorMessagesByFields(ConstraintViolationListInterface $constraintViolationList)
    {
        $errorMessagesByFields = array();
        $fields = array();
        
        for ($i = 0; $i < $constraintViolationList->count(); $i++) {
            $propertyName = $this->normalizeToCamelCase($constraintViolationList->get($i)->getPropertyPath());
            if (!in_array($propertyName, $fields)) {
                array_push($fields, $propertyName);
                $errorMessagesByFields[$propertyName] = array();
            }
        }
        
        for ($i = 0; $i < $constraintViolationList->count(); $i++) {
            $propertyName = $this->normalizeToCamelCase($constraintViolationList->get($i)->getPropertyPath());
            $errorMessagesByFields[$propertyName] = array_merge(
                $errorMessagesByFields[$propertyName],
                array($constraintViolationList->get($i)->getMessage()));
        }
        
        return $errorMessagesByFields;
    }
    
    protected function normalizeToCamelCase($string)
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $string)), '_');
    }
}
