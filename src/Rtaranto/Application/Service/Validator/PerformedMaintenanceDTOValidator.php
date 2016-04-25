<?php
namespace Rtaranto\Application\Service\Validator;

use AppBundle\Validator\Constraints\KmsDoNotExceedCurKmsDriven;
use Exception;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Range;

class PerformedMaintenanceDTOValidator extends Validator
{
    protected function validate($object)
    {
        $this->throwExceptionIfObjectNotInstanceOfPerformedMaintenanceDTO($object);
        
        $errorsByFields = array();
        $this->addKmsDoNotExceedCurKmsDrivenViolations($object, $errorsByFields);
        $this->addKmsRangeViolations($object, $errorsByFields);
        $this->addDateViolation($object, $errorsByFields);
        
        return $errorsByFields;
    }
    
    private function addKmsDoNotExceedCurKmsDrivenViolations($object, &$errors)
    {
       $kmsExceedsViolation = $this->symfonyValidator->validate(
            $object->getKmsDriven(),
            array(new KmsDoNotExceedCurKmsDriven(array('motorcycleId' => $object->getMotorcycleId())))
        );
        if (!$kmsExceedsViolation->has(0)) {
            return;
        }
        $errorMessage = $kmsExceedsViolation->get(0)->getMessage();
        if (!key_exists('kms_driven', $errors)) {
            $errors['kms_driven'] = array();
        }
        $errors['kms_driven'] = array_merge($errors['kms_driven'], array($errorMessage));
    }
    
    private function addKmsRangeViolations($object, &$errors)
    {
        $kmsRangeViolations = $this->symfonyValidator->validate(
            $object->getKmsDriven(),
            array(new Range(array('min' => 0, 'max' => 1000000)))
        );
        if (!$kmsRangeViolations->has(0)) {
            return;
        }
        
        if (!key_exists('kms_driven', $errors)) {
            $errors['kms_driven'] = array(); 
        }
        
        $firstErrorMessage = $kmsRangeViolations->get(0)->getMessage();
        $errors['kms_driven'] = array_merge($errors['kms_driven'], array($firstErrorMessage));
                
        if ($kmsRangeViolations->has(1)) {
            $secondErrorMessage = $kmsRangeViolations->get(1)->getMessage();
            $errors['kms_driven'] = array_merge($errors['kms_driven'], array($secondErrorMessage));
        }
    }
    
    private function addDateViolation($object, &$errors)
    {
        $dateViolation = $this->symfonyValidator->validate(
            $object->getDateAsString(),
            array(new Date())
        );
        if ($dateViolation->has(0)) {
            $errors['date'] = array($dateViolation->get(0)->getMessage());
        }
    }
    
    protected function getErrors($object)
    {
        $errorsByFields = $this->validate($object);
        return array_merge(
            array('code' => Response::HTTP_BAD_REQUEST, 'message' => 'Validation Failed'),
            array('errors' => $errorsByFields)
        );
    }
    
    private function throwExceptionIfObjectNotInstanceOfPerformedMaintenanceDTO($object)
    {
        if (!$object instanceof PerformedMaintenanceDTO) {
            throw new Exception('Object must ne an instance of PerformedMaintenanceDTO.');
        }
    }
}
