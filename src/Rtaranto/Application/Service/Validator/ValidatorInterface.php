<?php
namespace Rtaranto\Application\Service\Validator;

use Rtaranto\Application\Exception\ValidationFailedException;

interface ValidatorInterface
{
    public function isValid($object);
    public function getErrors($object);
    /**
     * @param object $object
     * @throws ValidationFailedException
     */
    public function throwValidationFailedIfNotValid($object);
}
